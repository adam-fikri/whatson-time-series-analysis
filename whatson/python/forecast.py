import pandas as pd
from pmdarima import auto_arima
import sys

df = pd.read_csv('data/topic/topics.csv')
topic = sys.argv[1]
periods = int(sys.argv[2])

dateTime = df['dateTime'].to_list()
topic = df[topic].to_list()

new_df = pd.DataFrame(topic, columns=['topic'],index=pd.to_datetime(dateTime))

def arimamodel(timeseries):
    return auto_arima(timeseries, start_p=1, d=1, start_q=1, max_p=7, max_d=7, max_q=7, start_P=1, D=1, start_Q=1, max_P=7, max_D=7, max_Q=7, seasonal=True, test="adf", trace=True, stepwise=True,  m=10, random_state=20)

model = arimamodel(new_df)

def getForecast(periods, timeseries, model):
    #forecast value
    fc = model.predict(n_periods=periods)
    #hourly index
    fc_ind = pd.date_range(timeseries.index[timeseries.shape[0]-1], periods=periods, freq="1H")
    #forecast dataframe
    return pd.DataFrame(fc, columns=['forecast'],index=fc_ind)

fc =getForecast(periods+1, new_df, model)

new_df.reset_index(level=0, inplace=True)
fc.reset_index(level=0, inplace=True)

new_df.to_csv('data/forecast/df.csv', index=False)
fc.to_csv('data/forecast/fc.csv', index=False)