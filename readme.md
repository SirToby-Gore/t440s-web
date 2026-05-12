# Requirements
* php
* bash
* sass

# Making your CV
Create a `cv.json` in the root of the repo.

create the base of your json.
```json
{
    "name": "john smith", // your name here
}
```

Then add an about me, or about you.
```json
{
    ...
    "about me": "I have been on the search for a job where I could eat bread all day long"
}
```

Then you'll want to add all of you experiences.
```json
{
    ...
    "experiences": [
        ...
    ]
}
```

For each experience, you add them like this.
```json
{
    ...
    "experiences": [
        ...
        {
            "title": "CEO",

            // then either a date from and until
            "date from": "AUG 2004",
            "date until": "SEP 2011", // set this to null or leave it blank to let it fill 
            // or you can have a date for a one off event
            "date": "NOV 2008",
            // do not use both!

            "location": "google", // the name of the company

            "description": "I was in charge of over seeing the hatchlings" // a description of what you did there
        }
    ]
}
```

It is very similar for education, start with.
```json
{
    ...
    "education": [
        ...
    ]
}
```

Then for each qualification.
```json
{
    ...
    "education": [
        ...
        {
            "title": "Masters in biscuit easting", // the name of the qualification

            // use the date from-util system 
            "date from": "SEP 1997",
            "date until": "JUL 2003", 

            "location": "cambridge", // where it was that you did it
            "description": "Level 3, we had to eat, make, and hoss biscuits at each other" // what final grade was and what it was
        }
    ]
}
```

Then you'll add your volunteering.
```json
{
    ...
    "volunteering": [
        ...
    ]
}
```

for each activity.
```json
{
    "volunteering": [
        ... 
        {
            "title": "pigeon hossing", // the name of what you did
            "date": "JUN 2016", // use the date system as from before
            "description": "we where hossing pigeon at the local streat toughs" // what your volunteering had in store
        }
    ]
}
```

Then add you contact details.
```json
{
    "contact": {
        "phone": "07478 766785", // your phone number
        "email": "email@example.com", // your email
        "linkedin": "your-name-id", // your id name on linked in
        "linkedin href": "https://linkedin.com/your-account", // the url to your linked in account
        "github": "spudEater-9000", // your git hub username
        "github href": "https://github/yourName" // the url to your git hub
    }
}
```

Then finally in the json add your passion, what you are passionate about.
```json
{
    "passion": "I love a good but of tea and crumpets" // write about you and your interests
}
```

# Turning your CV into HTML
In the root of the repo run.
```bash
./make_cv.sh

# then
cd src/html

# then open the file, here I use firefox
firefox index.html
```

# How to style it
```json
{
    ...
    "styles": {
        // a css colour
        // the colour used for the bulk of text
        "primary text colour": "black",

        // a css colour
        // the colour for text that isn't main content
        "secondary text colour": "darkslategrey",

        // a css colour
        // this is the colour for border and the solid block in the corner
        "primary colour": "green",

        // a css colour
        // this is the background colour for the whole document
        "background colour": "yellow",

        // a css absolute unit
        // this is the width of the borders in the document
        "border width": "0.2cm"
    }
}