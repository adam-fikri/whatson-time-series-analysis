import pickle
import json
import sys

#filePath = json.loads(sys.argv[1])['name']
filePath = sys.argv[1]

lda_model = pickle.load(open(filePath, 'rb'))

num_topics = 10
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