import pandas as pd

d = { 'Crop':['Soybeans','Canola','Wheat','Soybeans','Soybeans','Wheat',                                'Canola','Canola','Wheat','Soybeans','Canola','Wheat',                                    'Wheat','Canola','Wheat','Soybeans','Canola','Soybeans',                                  'Soybeans','Canola','Wheat','Soybeans','Canola','Wheat'],

      'Season':['Fall','Fall','Fall','Fall','Fall','Fall',                                                'Fall','Fall','Fall','Fall','Fall','Fall',                                                'Spring','Spring','Spring','Spring','Spring','Spring',                                    'Spring','Spring','Spring','Spring','Spring','Spring'],

      'Price':[11.27,10.95,6.65,11.10,10.80,6.50,

               11.05,9.98,7.02,11.44,10.46,6.37,

               6.23,10.30,6.20,11.44,9.97,11.24,

               11.50,10.24,6.55, 11.07,9.96,6.21] }


df = pd.DataFrame(d,columns=['Crop','Season','Buyer','Price'])

print(df)