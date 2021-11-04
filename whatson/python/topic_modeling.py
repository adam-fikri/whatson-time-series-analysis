import pandas as pd
import json
import sys
import pickle

from gensim import corpora
import numpy as np
import random

import gensim.models.ldamodel as lda

#import nltk and regex
import re #RegEx
from nltk import word_tokenize
from nltk.corpus import stopwords

#initialize all nltk variables
stopWrd = set(stopwords.words('english'))
unwanted = ['.',',','-','000']

#all text preprocessing function
def cleanTweet(text): #clean tweets from link, reserved word, hashtag(#) and mention(@)
    text = re.sub('@\S+',' ',text) #remove mention
    text = re.sub('#',' ',text) #remove hashtag
    text = re.sub('(RT|FAV)',' ',text) #remove reserved word
    text = re.sub('(www|http|https)(\S+)',' ',text) #remove link
    text = re.sub('&\S+;',' ',text) #remove character ref (&amp; &quot; &lt; etc)
    text = re.sub('[.]{2,}',' ',text) #remove repeated dot
    text = re.sub('[-]{2,}',' ',text) #remove repeated dash
    text = re.sub('[^a-zA-Z0-9.-]',' ',text) #remove other than letter. Should I keep number? 
    
    return text

def getTopic(sentence):
    bow = id2word.doc2bow(sentence)
    return max(lda_model[bow], key=lambda item: item[1])[0]

def getDateTime(x):
    if x['hour'] < 10:
        return str(x['date']) + ' 0' + str(x['hour']) + ':00:00'
    else:
        return str(x['date']) + ' ' + str(x['hour']) + ':00:00'

def processTextSimp(text): #for fast text processing
    text = word_tokenize(text.lower())
    
    text = [x for x in text if x not in stopWrd] #remove stopwords
    text = [x for x in text if x not in unwanted]
    
    return text

#end

#fetch tweets
fileName = sys.argv[1]
data = pd.read_csv(fileName, encoding='utf-8', parse_dates=['created_at'])
cleanTweets = data['full_text'].apply(cleanTweet)
tokenizedTweets = cleanTweets.apply(processTextSimp)
data['new_text'] = tokenizedTweets

seed = 40
random.seed(seed)

id2word = corpora.Dictionary(data['new_text'])
id2word.filter_extremes(no_below=5, no_above=0.05)

if bool(id2word) == False:
    id2word = corpora.Dictionary(data['new_text'])

corpus = [id2word.doc2bow(text) for text in data['new_text']]

num_topics = 10
lda_model = lda.LdaModel(corpus=corpus, id2word=id2word, num_topics=num_topics, random_state=seed)

needTopic = sys.argv[2];
if needTopic == 'y':
    with open('topics.pickle', 'wb') as file:
        pickle.dump(lda_model, file)

    data['topic'] = data['new_text'].apply(getTopic)

    data['year'] = data['created_at'].dt.year #to filter out
    data['month'] = data['created_at'].dt.month #to filter out
    data['hour'] = data['created_at'].dt.hour
    data['date'] = data['created_at'].dt.date

    dataNew = data.drop(data[(data['year']<2020) | ((data['year']==2020) & (data['month']<=11))].index)

    dataNew['dateTime'] = dataNew.apply(getDateTime,axis=1)

    top0 = dataNew[dataNew['topic'] == 0].groupby(['dateTime']).topic.count()
    top1 = dataNew[dataNew['topic'] == 1].groupby(['dateTime']).topic.count()
    top2 = dataNew[dataNew['topic'] == 2].groupby(['dateTime']).topic.count()
    top3 = dataNew[dataNew['topic'] == 3].groupby(['dateTime']).topic.count()
    top4 = dataNew[dataNew['topic'] == 4].groupby(['dateTime']).topic.count()
    top5 = dataNew[dataNew['topic'] == 5].groupby(['dateTime']).topic.count()
    top6 = dataNew[dataNew['topic'] == 6].groupby(['dateTime']).topic.count()
    top7 = dataNew[dataNew['topic'] == 7].groupby(['dateTime']).topic.count()
    top8 = dataNew[dataNew['topic'] == 8].groupby(['dateTime']).topic.count()
    top9 = dataNew[dataNew['topic'] == 9].groupby(['dateTime']).topic.count()

    topics = pd.DataFrame()
    topics['top0'] = top0
    topics['top1'] = top1
    topics['top2'] = top2
    topics['top3'] = top3
    topics['top4'] = top4
    topics['top5'] = top5
    topics['top6'] = top6
    topics['top7'] = top7
    topics['top8'] = top8
    topics['top9'] = top9

    topics = topics.fillna(0)
    topics.to_csv('../data/topic/topics.csv')


dictTopics = {}
term = 15
for i in range(num_topics):
    dictTopics[i] = {}
    #term = random.randint(17,20)
    topic = lda_model.show_topic(i,term)
    for j in range(len(topic)):
        dictTopics[i][j] = {}
        dictTopics[i][j]['term'] = topic[j][0]
        dictTopics[i][j]['freq'] = str(topic[j][1])

dictTopics = json.dumps(dictTopics)

print(dictTopics)