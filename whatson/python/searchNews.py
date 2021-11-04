import json
import sys
from newsapi import NewsApiClient

apiKey = 'API_KEY_HERE'

newsapi = NewsApiClient(api_key=apiKey)

q = sys.argv[1]

searchResults = newsapi.get_everything(q=q, page=2, language='en', sort_by='relevancy')

results_article = searchResults['articles']
results = {}

for i in range(len(results_article)):
    results[i] = {}
    results[i]['source'] = results_article[i]['source']['name']
    results[i]['title'] = results_article[i]['title']
    results[i]['description'] = results_article[i]['description']
    results[i]['date'] = results_article[i]['publishedAt']
    results[i]['url'] = results_article[i]['url']
    results[i]['imageUrl'] = results_article[i]['urlToImage']

results = json.dumps(results)
print(results)