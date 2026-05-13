#!/opt/lampp/htdocs/receipt_parser/.venv/bin/python

import os
import json
import pytesseract
from sys import argv
from typing import Any, Literal
from PIL import Image
from huggingface_hub import InferenceClient

# Configure Tesseract path
pytesseract.pytesseract.tesseract_cmd = r'/usr/bin/tesseract'

with open('.api_key', 'r') as file:
    api_key = file.read()

# Initialize Hugging Face client using the key from environment
client = InferenceClient(
    model="meta-llama/Meta-Llama-3-8B-Instruct", 
    api_key=api_key
)

def receipt_to_text(receipt_path):
    """Opens image, pre-processes it, and extracts text via OCR."""
    receipt_image = Image.open(receipt_path)
    receipt_image = receipt_image.convert('L')  # convert to greyscale
    # Upscale for better accuracy
    receipt_image = receipt_image.resize((receipt_image.width * 2, receipt_image.height * 2))  
    ocr_text = pytesseract.image_to_string(receipt_image)
    return ocr_text

def extract_receipt_data(ocr_text):
    """Sends OCR text to Llama-3 to structure into JSON."""
    system_instruction = (
        "You are an expert data extraction AI. Your job is to take raw OCR text from a receipt "
        "and convert it into a structured JSON format. Do not include any conversational text, "
        "markdown formatting blocks, or explanations. Return ONLY the raw JSON string."
    )
    
    user_prompt = f"""
    Analyze the following raw receipt text and extract the company name, 
    the items bought, their prices, and a general category for each item.
    
    Format your response EXACTLY like this JSON structure:
    {{  
        "company": "Name of the Company",
        "items": [
            {{
                "name": "Item Name",
                "price": 0.00,
                "category": "Category"
            }}
        ]
    }}

    Receipt Text:
    {ocr_text}
    """

    messages = [
        {"role": "system", "content": system_instruction},
        {"role": "user", "content": user_prompt}
    ]
    
    raw_output = ''
    
    try:
        response = client.chat_completion(
            messages=messages,
            max_tokens=1000,
            temperature=0.1
        )
        
        raw_output = response.choices[0].message.content or ''
        # Strip any accidental whitespace or markdown markers
        clean_output = raw_output.strip().replace('```json', '').replace('```', '')
        
        return json.loads(clean_output)

    except json.JSONDecodeError:
        return {"error": "JSON_DECODE_FAILURE", "raw": raw_output}
    except Exception as e:
        return {"error": str(e)}

if __name__ == "__main__":
    if len(argv) < 2:
        print(json.dumps({"error": "No file path provided"}))
    else:
        try:
            ocr_output = receipt_to_text(argv[1])
            parsed_receipt = extract_receipt_data(ocr_output)
            # Use json.dumps to ensure the output is valid JSON for PHP
            print(json.dumps(parsed_receipt))
        except Exception as e:
            print(json.dumps({"error": str(e)}))