import numpy as np
import pandas as pd

import re #RegEx
from nltk import word_tokenize
from nltk.corpus import stopwords
from nltk.stem import PorterStemmer

from textblob import TextBlob

stemmer  = PorterStemmer()
stopWrd = set(stopwords.words('english'))

def cleanTweet(text): #clean tweets from link, reserved word, hashtag(#) and mention(@)
    text = re.sub('@\S+',' ',text) #remove mention
    text = re.sub('#',' ',text) #remove hashtag
    text = re.sub('(RT|FAV)',' ',text) #remove reserved word
    text = re.sub('(www|http|https)(\S+)',' ',text) #remove link
    text = re.sub('&\S+;',' ',text) #remove character ref (&amp; &quot; &lt; etc)
    text = re.sub('[^a-zA-Z]',' ',text) #remove other than letter. Should I keep number? 
    
    return text

def processText(text):
    text = word_tokenize(text.lower())
    
    text = [x for x in text if x not in stopWrd] #remove stopwords
    
    newWords =[]
    for word in text:
        newWords.append(stemmer.stem(word))
    return newWords #return as stemmed token

def getPolarity(text):
    return TextBlob(text).sentiment.polarity

# < 0 = negative
# 0 = neutral
# > 0 = positive
def getSentiment(score):
    if score < 0:
        return 'Negative'
    elif score == 0:
        return 'Neutral'
    else:
        return 'Positive'

def getDateTime(x):
    if x['hour'] < 10:
        return str(x['date']) + ' 0' + str(x['hour']) + ':00:00'
    else:
        return str(x['date']) + ' ' + str(x['hour']) + ':00:00'

np.random.seed(500) #idk why
test = pd.read_csv('data/tweets.csv',encoding='utf-8', parse_dates=['created_at'])

cleanTweets = test['full_text'].apply(cleanTweet)
#tokenizedTweets = cleanTweets.apply(processText)

#for i in range(len(tokenizedTweets)):
#    tokenizedTweets[i] = ' '.join(tokenizedTweets[i])

test['new_text'] = cleanTweets

test['polarity'] = test['new_text'].apply(getPolarity)
test['result'] = test['polarity'].apply(getSentiment)

test['year'] = test['created_at'].dt.year #to filter out
test['month'] = test['created_at'].dt.month #to filter out
test['hour'] = test['created_at'].dt.hour
test['date'] = test['created_at'].dt.date

testNew = test.drop(test[(test['year']<2020) | ((test['year']==2020) & (test['month']<=11))].index)

testNew['dateTime'] = testNew.apply(getDateTime,axis=1)

pos = testNew[testNew['result']=='Positive']
neg = testNew[testNew['result']=='Negative']
neu = testNew[testNew['result']=='Neutral']

total = [len(pos.index), len(neg.index), len(neu.index)]
freq = pd.DataFrame(total, columns=['freq'])
freq.to_csv('data/sentiment/sentiment_freq.csv', columns=['freq'], index=False)

pos.to_csv('data/sentiment/pos_tweets.csv', columns=['created_at','full_text','polarity','screen_name'], index=False)
neg.to_csv('data/sentiment/neg_tweets.csv', columns=['created_at','full_text','polarity','screen_name'], index=False)
neu.to_csv('data/sentiment/neu_tweets.csv', columns=['created_at','full_text','polarity','screen_name'], index=False)

pos = pos.groupby(['dateTime']).result.count()
neg = neg.groupby(['dateTime']).result.count()
neu = neu.groupby(['dateTime']).result.count()

sentiments = pd.DataFrame()
sentiments['pos'] = pos
sentiments['neg'] = neg
sentiments['neu'] = neu
sentiments = sentiments.fillna(0)

sentiments.to_csv('data/sentiment/sentiments.csv')