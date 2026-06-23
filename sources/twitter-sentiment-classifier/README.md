# twitter-sentiment-classifier

Source: [Matheos-Ioannides/epl499_group_project_TeamWIP](https://github.com/Matheos-Ioannides/epl499_group_project_TeamWIP)
Pinned commit: `cc1651262581da8fef830e47031310b216557bf5`
License: unknown
Import mode: vendored-snapshot
Status: candidate (pending review)

## What this is

University group project (EPL499) — binary Twitter sentiment classifier (Positive vs. Negative).
Collaborative work; code contributed collaboratively.

## What was imported

Full repository including:
- `EPL499_Assignment_2_...ipynb` — complete ML pipeline notebook
- `twitter_sentiment_train.csv` / `twitter_sentiment_test.csv` — datasets
- `profanity.txt` — 458-word profanity lexicon used as a feature
- `README.md` — project summary and results

## Key technical content

**Pipeline:** ekphrasis tokenizer → 15 handcrafted features + Word2Vec (100d) + BoW → L1 feature selection → Logistic Regression → ~85% accuracy

**Notable patterns:**
- `analyzer=lambda x: x` in CountVectorizer to bypass internal tokenization
- L1 logistic regression for automatic feature selection (SelectFromModel)
- 5-fold cross-validation before final test evaluation
- TextBlob polarity/subjectivity as features
- NLTK opinion lexicon positive/negative word counts
- ekphrasis `<elongated>` annotation as a novel feature

## upstream/

Clean snapshot at the pinned commit. Do not modify.

## adapted/

Extracted utilities, improvements, and integrations go here.
