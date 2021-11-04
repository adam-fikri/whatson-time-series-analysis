import pandas as pd
import sys
import json

num = int(sys.argv[1])
columns = ['full_text','polarity','sector']
dictTop = {}
pos = {}
neg = {}
neu = {}

newsHandle = ['YahooNews', 'cnni', 'nytimes', 'FoxNews', 'NBCNews']
sportHandle = ['espn', 'SkySportsNews', 'NBCSports', 'BBCSport', 'SkySports']
scitechHandle = ['ScienceNews', 'ReutersScience', 'TechCrunch', 'newscientist', 'VentureBeat']
politicsHandle = ['CNNPolitics', 'ABCPolitics', 'BBCPolitics', 'nytpolitics', 'bpolitics']
entertainmentHandle = ['enews', 'IGN', 'nbc', 'screenrant', 'EW']
travelHandle = ['BTN_News', 'CNNTravel', 'travel_biz_news', 'USNewsTravel']
businessHandle = ['business', 'TheEconomist', 'economics', 'markets', 'FinancialTimes']
healthcareHandle = ['healthmagazine', 'NPRHealth', 'Reuters_Health', 'USNewsHealth', 'bbchealth']

def getSector(handle):
    if handle in newsHandle:
        return 'news'
    
    if handle in sportHandle:
        return 'sport'
    
    if handle in scitechHandle:
        return 'science & tech'
    
    if handle in politicsHandle:
        return 'politics'
    
    if handle in entertainmentHandle:
        return 'entertainment'
    
    if handle in travelHandle:
        return 'travel'
    
    if handle in businessHandle:
        return 'business'
    
    if handle in healthcareHandle:
        return 'healthcare'

temp = pd.read_csv('../data/sentiment/pos_tweets.csv',encoding='utf-8')
temp['sector'] = temp['screen_name'].apply(getSector)
temp = temp.sort_values(by=['polarity'],ascending=False)
temp = temp[columns].head(num)

for i in range(num):
    pos[i] = {}
    pos[i]['full_text'] = temp.iloc[i]['full_text']
    pos[i]['polarity'] = temp.iloc[i]['polarity']
    pos[i]['sector'] = temp.iloc[i]['sector']

temp = pd.read_csv('../data/sentiment/neg_tweets.csv',encoding='utf-8')
temp['sector'] = temp['screen_name'].apply(getSector)
temp = temp.sort_values(by=['polarity'],ascending=True)
temp = temp[columns].head(num)

for j in range(num):
    neg[j] = {}
    neg[j]['full_text'] = temp.iloc[j]['full_text']
    neg[j]['polarity'] = temp.iloc[j]['polarity']
    neg[j]['sector'] = temp.iloc[j]['sector']

temp = pd.read_csv('../data/sentiment/neu_tweets.csv',encoding='utf-8')
temp['sector'] = temp['screen_name'].apply(getSector)
temp = temp.sort_values(by=['polarity'],ascending=True)
temp = temp[columns].head(num)

for k in range(num):
    neu[k] = {}
    neu[k]['full_text'] = temp.iloc[k]['full_text']
    neu[k]['polarity'] = temp.iloc[k]['polarity']
    neu[k]['sector'] = temp.iloc[k]['sector']

dictTop['pos'] = pos
dictTop['neg'] = neg
dictTop['neu'] = neu

dictTop = json.dumps(dictTop)
print(dictTop)