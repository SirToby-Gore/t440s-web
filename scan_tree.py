import os
import argparse
import time

def scan_and_print(path: str, skip_filetypes: list[str], skip_dirs: list[str]) -> None:
    # ⚠️ FIX 1: Directory skipping check should be done *before* listing contents for sub-paths, 
    # but we'll stick to skipping children in the loop for recursive logic.
    
    print(f'{skip_dirs=}')

    try:
        # Get all children in the current directory
        children = os.listdir(path)
    except:
        return
        
    for child in children:
        sub_path = os.path.join(path, child)
        
        # --- Directory Skipping Logic ---
        if os.path.isdir(sub_path):
            # 💡 FIX 2: Check the directory name (`child`) against the list of skip names.
            if child in skip_dirs:
                print(f"--- Skipping directory: {sub_path} ---")
                continue # Skip to the next item in the current directory
            
            # Recursive call only if not skipped
            scan_and_print(sub_path, skip_filetypes, skip_dirs)
            
        # --- File Skipping Logic ---
        else:
            # 💡 FIX 3: Use os.path.splitext() for reliable extension extraction.
            _, extension = os.path.splitext(sub_path)
            
            # Remove the leading dot and check if it's in the skip list
            # We check extension[1:] because extension will be like '.py'
            if extension and extension[1:] in skip_filetypes: 
                print(f"--- Skipping filetype: {sub_path} (.{extension[1:]}) ---")
                continue # Skip to the next item
            
            # Print file contents
            print(f'```{sub_path}')
            try:
                with open(sub_path, 'r', encoding='utf-8') as file:
                    print(file.read())
            except Exception as e:
                 print(f"--- Error reading file {sub_path}: {e} ---")
            print('```\n')

# The main() function remains unchanged and is correct for parsing arguments
def main():
    parser = argparse.ArgumentParser(
        description="Scans a directory recursively, prints contents, and allows skipping certain file types and directories.",
        formatter_class=argparse.RawTextHelpFormatter
    )
    
    # Optional argument for skipping file extensions
    parser.add_argument(
        '--skip-filetypes', 
        nargs='*', # 0 or more arguments
        default=[], 
        help="A space-separated list of file extensions to skip (e.g., txt json log). Do NOT include the leading dot."
    )
    
    # Optional argument for skipping directories by name
    parser.add_argument(
        '--skip-dirs', 
        nargs='*', # 0 or more arguments
        default=[], 
        help="A space-separated list of directory *names* to skip (e.g., venv __pycache__ .git)."
    )

    args = parser.parse_args()
    
    # Your original script scans './', we'll keep that as the default start path
    start_path = './'

    # Pass the parsed arguments to the recursive function
    scan_and_print(start_path, args.skip_filetypes, args.skip_dirs)

if __name__ == '__main__':
    main()