import pandas as pd
import pickle
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
data = pd.read_csv('data/tweets.csv',encoding='utf-8', parse_dates=['created_at'])

cleanTweets = data['full_text'].apply(cleanTweet)
tokenizedTweets = cleanTweets.apply(processText)

for i in range(len(tokenizedTweets)):
    tokenizedTweets[i] = ' '.join(tokenizedTweets[i])

data['new_text'] = tokenizedTweets

vector = vectorizer.transform(data['new_text'])
pred = lsvm.predict(vector)
data['sector'] = pred
data['sector'] = data['sector'].apply(toSector)

data['year'] = data['created_at'].dt.year #to filter out
data['month'] = data['created_at'].dt.month #to filter out
data['hour'] = data['created_at'].dt.hour
data['date'] = data['created_at'].dt.date

dataNew = data.drop(data[(data['year']<2020) | ((data['year']==2020) & (data['month']<=11))].index)

def getDateTime(x):
    if x['hour'] < 10:
        return str(x['date']) + ' 0' + str(x['hour']) + ':00:00'
    else:
        return str(x['date']) + ' ' + str(x['hour']) + ':00:00'

dataNew['dateTime'] = dataNew.apply(getDateTime,axis=1)

news = dataNew[dataNew['sector']=='news']
sports = dataNew[dataNew['sector']=='sports']
scitech = dataNew[dataNew['sector']=='science & tech']
politics = dataNew[dataNew['sector']=='politics']
entertainment = dataNew[dataNew['sector']=='entertainment']
travel = dataNew[dataNew['sector']=='travel']
business = dataNew[dataNew['sector']=='business']
healthcare = dataNew[dataNew['sector']=='healthcare']

total=[len(news.index), len(sports.index), len(scitech.index), len(politics.index), len(entertainment.index), len(business.index), len(travel.index), len(healthcare.index)]
freq = pd.DataFrame(total, columns=['freq'])
freq.to_csv('data/sector/sector_freq.csv', columns=['freq'], index=False)

columns = ['created_at','full_text']
news.to_csv('data/sector/news_tweets.csv', columns=columns, index=False)
sports.to_csv('data/sector/sports_tweets.csv', columns=columns, index=False)
scitech.to_csv('data/sector/scitech_tweets.csv', columns=columns, index=False)
politics.to_csv('data/sector/politics_tweets.csv', columns=columns, index=False)
entertainment.to_csv('data/sector/entertainment_tweets.csv', columns=columns, index=False)
travel.to_csv('data/sector/travel_tweets.csv', columns=columns, index=False)
business.to_csv('data/sector/business_tweets.csv', columns=columns, index=False)
healthcare.to_csv('data/sector/healthcare_tweets.csv', columns=columns, index=False)

news = news.groupby(['dateTime']).sector.count()
sports = sports.groupby(['dateTime']).sector.count()
scitech = scitech.groupby(['dateTime']).sector.count()
politics = politics.groupby(['dateTime']).sector.count()
entertainment = entertainment.groupby(['dateTime']).sector.count()
travel = travel.groupby(['dateTime']).sector.count()
business = business.groupby(['dateTime']).sector.count()
healthcare = healthcare.groupby(['dateTime']).sector.count()

sectors = pd.DataFrame()

sectors['news'] = news
sectors['sports'] = sports
sectors['scitech'] = scitech
sectors['politics'] = politics
sectors['entertainment'] = entertainment
sectors['travel'] = travel
sectors['business'] = business
sectors['healthcare'] = healthcare
sectors = sectors.fillna(0)

sectors.to_csv('data/sector/sectors.csv')