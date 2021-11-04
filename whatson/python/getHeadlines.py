import json
import sys
from newsapi import NewsApiClient

apiKey = 'API_KEY_HERE'

newsapi = NewsApiClient(api_key=apiKey)

category = sys.argv[1]

top_headlines = newsapi.get_top_headlines(page=2, language='en', country='us', category=category)

headlines_article = top_headlines['articles']
headlines = {}

for i in range(len(headlines_article)):
    headlines[i] = {}
    headlines[i]['source'] = headlines_article[i]['source']['name']
    headlines[i]['title'] = headlines_article[i]['title']
    headlines[i]['description'] = headlines_article[i]['description']
    headlines[i]['date'] = headlines_article[i]['publishedAt']
    headlines[i]['url'] = headlines_article[i]['url']
    headlines[i]['imageUrl'] = headlines_article[i]['urlToImage']

headlines = json.dumps(headlines)
print(headlines)