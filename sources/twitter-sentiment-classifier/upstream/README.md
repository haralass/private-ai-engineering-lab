# EPL499 Final Project Overview


## Table of Contents
* List of Members
* Project Overview
* Feature types used
* Implemented Model
* Findings and Results

## List of Members
* academiccs: Joel Jacob(UC1071342)
* Matheos-Ioannides: Matheos Ioannides (UC1070137)
* haralass: Haralambos Pieri (UC111670) 

## Project Overview
The project goal was to build and evaluate a sentiment classifer for Twitter.
By applying various NLP methods we were able to create such a model and eventually evaluate it.


## Feature types Used

The broad categories of feature types include:

* Lexical Features
* Structural Features


Regrading our own features (of which all are lexical), they were:

* Elongated word count


Explanation:These features were used to identify positive tweets.

## Implemented Model
The model we implemented is a  **logistic regression model** with many different features belonging to the above categories.Besides the guideline features a few additional features of our own were implemented.

After applying the necessary pre-processing  using the special tokenizer **ekphrasis** and getting the results from the various feature enabling methods we applied feature selection in order to isolate the most impactful features.

## Findings and Results
Our results are as follows:

 Our model after applying the above steps was able to achieve an average percision of 85%.
 
 Of the added features none seem to have meaningfully raised accuracy.
 
 Sarcasm was a phenomenon our model was in general unable to understand reliably.Twitter as a platform contains many examples of sarcastic speech and as such being able to cope with it would be essential for any compentent model. 












