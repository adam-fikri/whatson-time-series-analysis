import pandas as pd
import pickle
import sys
import re #RegEx
from nltk import word_tokenize
from nltk.corpus import stopwords
from nltk.stem import PorterStemmer

stemmer  = PorterStemmer()
stopWrd = set(stopwords.words('english'))

def toSector(num):
    if num == 0:
        return 'news'
    elif num == 1:
        return 'sports'
    elif num == 2:
        return 'science & tech'
    elif num == 3:
        return 'politics'
    elif num == 4:
        return 'entertainment'
    elif num == 5:
        return 'travel'
    elif num == 6:
        return 'business'
    elif num == 7:
        return 'healthcare'

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

lsvm = pickle.load(open('python/sector_model/lsvm_old.pickle', 'rb'))
vectorizer = pickle.load(open('python/sector_model/vector_old.pickle', 'rb'))

fileName = sys.argv[1]

data = pd.read_csv(fileName,encoding='utf-8', parse_dates=['created_at'])

cleanTweets = data['full_text'].apply(cleanTweet)
tokenizedTweets = cleanTweets.apply(processText)

for i in range(len(tokenizedTweets)):
    tokenizedTweets[i] = ' '.join(tokenizedTweets[i])

data['new_text'] = tokenizedTweets

vector = vectorizer.transform(data['new_text'])
pred = lsvm.predict(vector)
data['sector'] = pred
data['sector'] = data['sector'].apply(toSector)

news = data[data['sector'] == 'news']
sports = data[data['sector'] == 'sports']
scitech = data[data['sector'] == 'science & tech']
politics = data[data['sector'] == 'politics']
entertainment = data[data['sector'] == 'entertainment']
travel = data[data['sector'] == 'travel']
business = data[data['sector'] == 'business']
healthcare = data[data['sector'] == 'healthcare']

row={}
row['news'] = len(news.index)
row['sports'] = len(sports.index)
row['scitech'] = len(scitech.index)
row['politics'] = len(politics.index)
row['entertainment'] = len(entertainment.index)
row['travel'] = len(travel.index)
row['business'] = len(business.index)
row['healthcare'] = len(healthcare.index)

freqFile = sys.argv[2]
freq = pd.DataFrame()
freq = freq.append(row, ignore_index=True)
freq.to_csv(freqFile, index=False)