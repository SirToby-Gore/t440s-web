// this is the main element for the calculator to sit in in the DOM
const mainElement = document.body.getElementsByTagName('section')[0];

// this is the average carbon footprint form the DOM 
const averageCarbonFootprint = parseFloat(document.getElementById('avg-cbn-ftp')?.innerText ?? '8.2');

// if the main tag is not defined in the DOM
if (mainElement == null) {
    // creates a new main element
    const mainElement = document.createElement('section');

    // gives it a correct class
    mainElement.classList.add('calculation');

    // adds it to body
    document.body.appendChild(mainElement);
}

/*
 * Calculator Body
 * this is a body used for the calculator class with built in DOM manipulation
 * this holds some internal shorthand for certain elements
 **/
class CalculatorBody {
    // this is body that the calculator uses for its content
    body;

    // holds the question and its info
    questionContainer;
    questionNumber;
    questionTitle;
    questionPercentMarker;
    
    // holds the possible answers to the current question
    answersContainer;

    // creates and adds all the calculator components to the DOM
    constructor() {
        this.body = document.createElement('div');
        this.body.classList.add('calculator');
        
        mainElement.appendChild(this.body);

        this.questionContainer = document.createElement('div');
        this.questionContainer.classList.add('question');
        this.body.appendChild(this.questionContainer);
    
        const markerContainer = document.createElement('div');
        markerContainer.classList.add('marker-container');
        this.questionContainer.appendChild(markerContainer)
        
        this.questionNumber = document.createElement('h2');
        this.questionNumber.classList.add('number');
        markerContainer.appendChild(this.questionNumber)
        
        this.questionPercentMarker = document.createElement('h2');
        this.questionPercentMarker.classList.add('percent-marker');
        markerContainer.appendChild(this.questionPercentMarker);

        this.questionTitle = document.createElement('h2');
        this.questionTitle.className = 'title capitalise';
        this.questionContainer.appendChild(this.questionTitle);
        
        this.answersContainer = document.createElement('div');
        this.answersContainer.classList.add('answers');
        this.body.appendChild(this.answersContainer);
    }

    /*
     * takes in the args of:
     *      question: Map<string, string | Map<string, number>>
     *          for the question to ask the user
     *      question number: number
     *          this is current question number
     *      number of question: number
     *          this is the total number of question the user will or has been asked
     * this method will return a promise for the number the answer their choice corresponds to
     **/
    async getUserInputFromQuestion(question, questionNumber, numberOfQuestions) {
        // empties out the answers
        this.answersContainer.innerHTML = '';

        this.displayQuestion(question, questionNumber, numberOfQuestions);

        return new Promise((resolve) => {
            for (const answer in question.answers) {
                if (!Object.prototype.hasOwnProperty.call(question.answers, answer)) {
                    continue;    
                }
                const value = question.answers[answer];

                let button = document.createElement('button');
                button.type = 'button';
                button.innerText = answer;
                button.className = 'secondary button';
                button.addEventListener('click', () => {
                    resolve(value);
                })
                this.answersContainer.appendChild(button);
            }
       });
    }
    
    /*
     * takes in arguments for:
     *      question: Map<string, string | Map<string, number>>
     *          the question to ask the user
     *      question number: number
     *          the current number of question
     *      number of questions: number
     *          the number of questions the user has or will be asked
     * this displays the question in the question bar at the top of the calculator
     */
    displayQuestion(question, questionNumber, numberOfQuestions) {
        this.questionTitle.innerText = question.question.toString();

        this.questionNumber.innerText = questionNumber.toString();

        // calculates the percentage to a whole number
        this.questionPercentMarker.innerText = Math.trunc(((questionNumber - 1)  / numberOfQuestions) * 100).toString();
    }

    /*
     * takes argument for:
     *      total: number
     *          the carbon footprint score the user got in the end
     * this outputs the user's final score the page for them to see along with the average score
     **/
    displayScore(total) {
        // clears the body
        this.body.innerHTML = '';

        const resultsTitle = document.createElement('h2');
        resultsTitle.classList.add('title');
        resultsTitle.innerText = 'Results are in.';
        mainElement.insertBefore(resultsTitle, this.body);
        
        const userScore = document.createElement('div');
        userScore.classList.add('result');
        userScore.innerHTML = `<p>Your carbon footprint is:</p><p class="score">${total}</p>`;
        this.body.appendChild(userScore)

        const averageScore = document.createElement('div');
        averageScore.classList.add('result');
        averageScore.innerHTML = `<p>Average carbon footprint is:</p><p class="score">${averageCarbonFootprint}</p>`
        this.body.appendChild(averageScore);

        const buttonsContainer = document.createElement('div');
        buttonsContainer.classList.add('buttons');
        mainElement.appendChild(buttonsContainer);

        const reCalcButton = document.createElement('button');
        reCalcButton.type = 'button';
        reCalcButton.className = 'secondary button';
        reCalcButton.addEventListener('click', () => {
            // reloads the page to reset the calculator
            window.location.href = './';
        })
        reCalcButton.innerText = 'Re-calculate'
        buttonsContainer.appendChild(reCalcButton);

        const saveButton = document.createElement('button');
        saveButton.type = 'button';
        saveButton.className = 'primary button';
        saveButton.addEventListener('click', () => {
            window.location.href = `save_score.php?score=${total}`;
        });
        saveButton.innerText = 'Do something about it';
        buttonsContainer.appendChild(saveButton);
    }
}

/*
 * a class to calculate the user's carbon footprint
 **/
class Calculator {
    // add more questions as needed
    questions = [
        {
            question: 'what type of car do you drive?',
            answers: {
                'small petrol car': 1.2,
                'petrol car': 1.4,
                'diesel': 1.8,
                'electric': 0.3,
                'hydrogen': 0.2,
                'N/A': 0,
            }
        },
        {
            question: 'how often do you eat meat?',
            answers: {
                'every a meal': 2.6,
                'every a day': 2.1,
                'every every few days': 1.5,
                'every a week': 1.1,
                'less then once a week': 0.3,
                'N/A': 0,
            }
        },
        {
            question: "how often do you fly for travel?",
            answers: {
                "multiple times a month": 3.0,
                "once a month": 2.5,
                "few times a year": 1.5,
                "once a year": 1.0,
                "rarely or never": 0.5,
                "N/A": 0
            }
        },
        {
            question: "how do you usually heat your home?",
            answers: {
                "gas heating": 2.3,
                "electric heating": 2.0,
                "wood burning stove": 1.5,
                "heat pump": 0.5,
                "solar power": 0.1,
                "N/A": 0
            }
        },
        {
            question: "how often do you use public transport?",
            answers: {
                "daily": 0.9,
                "few times a week": 0.7,
                "few times a month": 0.5,
                "rarely": 0.3,
                "never": 0.1,
                "N/A": 0
            }
        },
        {
            question: "how much of your electricity comes from renewable sources?",
            answers: {
                "100% renewable": 0.2,
                "50-99% renewable": 0.5,
                "less than 50% renewable": 1.0,
                "none": 1.5,
                "N/A": 0
            }
        },
        {
            question: "how much do you recycle?",
            answers: {
                "always": 0.2,
                "mostly": 0.5,
                "sometimes": 1.0,
                "rarely": 1.5,
                "never": 2.0,
                "N/A": 0
            }
        }
        
    ];
    body; 
    total;
    
    constructor() {
        this.body = new CalculatorBody();
        this.total = 0;
    }

    /*
     * shows all the questions in turn to the user and adds their returned value to the score
     **/
    async startQuestions() {
        // loops over the questions
        for (let i = 0; i < this.questions.length; i++) {
            const QUESTION = this.questions[i];

            // gets the user input
            const userInput = await this.body.getUserInputFromQuestion(QUESTION, i+1, this.questions.length);

            // updates the total
            this.total += userInput;
        }
    }

    // displays the score
    displayScore() {
        this.body.displayScore(Number.parseFloat(this.total.toPrecision(2)));
    }
}

function main() {
    // inits the carbon footprint Calculator
    const calculator = new Calculator();
    calculator.startQuestions().then( () => {
        calculator.displayScore();
    });
}

main();