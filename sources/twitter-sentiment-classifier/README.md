# twitter-sentiment-classifier

Source: [Matheos-Ioannides/epl499_group_project_TeamWIP](https://github.com/Matheos-Ioannides/epl499_group_project_TeamWIP)
Pinned commit: `cc1651262581da8fef830e47031310b216557bf5`
License: unknown
Import mode: local-research-only
Status: candidate (license pending)

## What this is

University group project (EPL499) — binary Twitter sentiment classifier (Positive vs. Negative).
Collaborative work; code contributed collaboratively.

## What was studied locally

No upstream source snapshot is committed here. The local clone was used to study:
- `EPL499_Assignment_2_...ipynb` — ML pipeline notebook
- `twitter_sentiment_train.csv` / `twitter_sentiment_test.csv` — datasets
- `profanity.txt` — profanity lexicon used as a feature
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

## Repository handling

Keep any local clone under the gitignored `external-sources/` directory. Do not commit upstream code while the license remains unknown.
