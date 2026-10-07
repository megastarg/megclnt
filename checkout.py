import base64
import concurrent.futures
import random
import uuid
from multiprocessing.pool import ThreadPool
from urllib.request import Request, urlopen
import logging
from logging.handlers import RotatingFileHandler
import traceback
import gzip
import re
import json
import datetime
import pytz
import urllib.parse
import ssl
import threading
from threading import Thread
#import copy
#import socket
import os
if os.name=="nt":
    import httpx
else:
    from curl_cffi import requests
import urllib
import asyncio
import aiohttp

import http.cookies



ssl._create_default_https_context = ssl._create_unverified_context

logger = logging.getLogger("Rotating Log")

def docheckout(lid, pid, qty, cookie):
    try:
        simple_cookie = http.cookies.SimpleCookie(cookie.strip())
        cookie_jar = {}
        for key, morsel in simple_cookie.items():
            cookie_jar[key] = morsel.value

        data = '{"checkoutType":"PHYSICAL","cartRequest":{"pageType":"CartPage","cartContext":{"' + lid + '":{"assessmentContextId":null,"cashifyDiscountApplied":false,"exchangeContext":null,"exchangeContextId":null,"offerId":null,"parentContext":null,"parentProductContext":null,"payWithEMISelected":null,"previousQuantity":0,"productId":"'+pid+'","quantity":'+str(qty)+',"reverseBuyingType":null,"selectedActions":null,"shopId":null,"shopListId":null,"shopListItemId":null,"superCoinSelected":null,"vulcanDiscountApplied":false}}}}'
        binary_data = data.encode('utf-8')

        myurl = "https://www.flipkart.com/api/5/checkout?loginFlow=false"

        header = {
                    "Connection": "keep-alive",
                    'User-Agent': 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36',
                    "Accept-Language": "en-GB,en;q=0.9",
                    'sec-ch-ua': '"Google Chrome";v="117", "Not;A=Brand";v="8", "Chromium";v="117"',
                    'sec-ch-ua-platform': 'Android',
                    "Accept": "*/*",
                    "Accept-Encoding": "gzip",
                    "Content-Type": "application/json; charset=UTF-8",
                    "X-User-Agent": "Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36 FKUA/msite/0.0.3/msite/Mobile",
                    "Referer": "https://www.flipkart.com/",
                    "Origin": "https://www.flipkart.com",
                    "Sec-Fetch-Site": "same-site",
                    "Sec-Fetch-Mode": "cors",
                    "Sec-Fetch-Dest": "empty"
                  }



        l = list(header.items())
        random.shuffle(l)
        d_shuffled = dict(l)

        if os.name=="nt":
            try:
                proxies = {"http://": "http://127.0.0.1:8888", "https://": "http://127.0.0.1:8888"}
                proxies = {}
                r = httpx.post(myurl, data=data, cookies=cookie_jar, headers=d_shuffled, verify=False, proxies=proxies)
            except Exception as e:
                r = httpx.post(myurl, data=data, cookies=cookie_jar, headers=d_shuffled, verify=False, proxies=proxies)
        else:
            try:
                r = requests.post(myurl, data=data, cookies=cookie_jar, headers=d_shuffled, verify=False, impersonate="chrome110")
            except:
                r = requests.post(myurl, data=data, cookies=cookie_jar, headers=d_shuffled, verify=False, impersonate="chrome110")

        html=r.text

        jsonarray = json.loads(html)

        try:
            title = jsonarray["RESPONSE"]["orderSummary"]["requestedStores"][0]["buyableStateItems"][0].get("mainTitle", "")
        except:
            title = ""

        try:
            email = jsonarray["RESPONSE"]["accountInfo"].get("emailId", "none")

            num = jsonarray["RESPONSE"]["accountInfo"].get("smsNotifyNumber", "none")

            if email==None:
                email = num
            elif email!=None and num != None:
                email = email + " " + num
        except Exception as e:
            email = ""

        try:
            pincode = jsonarray["RESPONSE"]["addressData"].get("pincode", "")
        except:
            pincode = ""

        try:
            filterapplied = re.search("\"grandTotal\":(.*?),", html)
            total = filterapplied[1]
        except Exception as e:
            total = 999999

        incart = False

        if html.find('"errorCode":null')!=-1 and html.find('address')!=-1 and html.find('cartItemRefId')!=-1 and html.find("EMPTY_CHECKOUT_CART")==-1:
            incart=True
            print(f"Checkout SUCCESS ({email}): Added to cart. Proceeding to get payment token.")
        else:
            incart = False
            print(f"Checkout FAILED ({email}): Could not add item to cart. (Cookie might be invalid or item out of stock)")

        if incart == True:
            myurl = "https://www.flipkart.com/api/3/checkout/paymentToken"

            if os.name == "nt":
                try:
                    #proxies = {"http://": "http://127.0.0.1:8888", "https://": "http://127.0.0.1:8888"}
                    proxies = {}
                    r = httpx.get(myurl, cookies=cookie_jar, headers=d_shuffled, verify=False, proxies=proxies)
                except Exception as e:
                    r = httpx.get(myurl, cookies=cookie_jar, headers=d_shuffled, verify=False, proxies=proxies)
            else:
                try:
                    r = requests.get(myurl, cookies=cookie_jar, headers=d_shuffled, verify=False, impersonate="chrome110")
                except:
                    r = requests.get(myurl, cookies=cookie_jar, headers=d_shuffled, verify=False, impersonate="chrome110")

            html = r.text

            jsonarray = json.loads(html)

            token = jsonarray["RESPONSE"]["getPaymentToken"].get("token", "tokennull")
            myurl = "https://1.pay.payzippy.com/fkpay/api/v3/payments/paywithdetails?token="+token

            header = {
                "Connection": "keep-alive",
                'User-Agent': 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36',
                "Accept-Language": "en-GB,en;q=0.9",
                'sec-ch-ua': '"Google Chrome";v="117", "Not;A=Brand";v="8", "Chromium";v="117"',
                'sec-ch-ua-platform': 'Android',
                'x-device-source': 'msite',
                'x-ab-experiments': '{"egv_travel_enabled":1,"gpay_integration":2,"wallet_experiment":1,"NU_COD_DEFAULT":1,"vpa_payments_page":1,"SC_PAY":1,"scpay_v2_experiment":2}',
                "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
                "Accept-Encoding": "gzip",
                "Content-Type": "application/json; charset=UTF-8",
                "X-User-Agent": "Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36 FKUA/msite/0.0.3/msite/Mobile",
                "Referer": "https://www.flipkart.com/"
            }

            data='{"upi_details":{"app_code":"PHONEPE","package_name":"com.phonepe.app"},"payment_instrument":"PHONEPE","token":"' + token + '","section_info":{"section_name":"OTHERS"},"user_selected_adjustment_ids":[]}'


            if os.name == "nt":
                try:
                    #proxies = {"http://": "http://127.0.0.1:8888", "https://": "http://127.0.0.1:8888"}
                    proxies = {}
                    r = httpx.post(myurl, cookies=cookie_jar, data=data, headers=d_shuffled, verify=False, proxies=proxies)
                except Exception as e:
                    r = httpx.post(myurl, cookies=cookie_jar, data=data, headers=d_shuffled, verify=False, proxies=proxies)
            else:
                try:
                    r = requests.post(myurl, cookies=cookie_jar, data=data, headers=d_shuffled, verify=False, impersonate="chrome110")
                except:
                    r = requests.post(myurl, cookies=cookie_jar, data=data, headers=d_shuffled, verify=False, impersonate="chrome110")

            html = r.text

            jsonarray = json.loads(html)

            phonepeurl = jsonarray["primary_action"].get("url", "")

            currenttime = datetime.datetime.now(pytz.timezone("Asia/Kolkata")).strftime("%d/%m/%Y %H:%M:%S")

            if phonepeurl!="" and phonepeurl!=None:
                myurl="http://139.59.59.189/phonepesave.php?itemid="+lid+"&text=" + urllib.parse.quote(currenttime + " => <u><font color=maroon><b>/BIS/"+ pincode+"/</b></font></u> => <b>" + email + " [" + pincode + "]</b> - "+ title + " ["+lid+"]<br><b><font color=maroon>" + str(total) + " Rs. </font></b><br><a href=" + phonepeurl + ">" + phonepeurl + "</a>")
                if os.name == "nt":
                    try:
                        # proxies = {"http://": "http://127.0.0.1:8888", "https://": "http://127.0.0.1:8888"}
                        proxies = {}
                        r = httpx.get(myurl, headers=d_shuffled, verify=False, proxies=proxies)
                    except Exception as e:
                        r = httpx.get(myurl, headers=d_shuffled, verify=False, proxies=proxies)
                else:
                    try:
                        r = requests.get(myurl, headers=d_shuffled, verify=False, impersonate="chrome110")
                    except:
                        r = requests.get(myurl, headers=d_shuffled, verify=False, impersonate="chrome110")

                myurl = "https://api.telegram.org/bot6473432350:AAF_zqX3zKmnnyKH4oKn-foB7xDsY-Nfq1s/sendMessage?chat_id=-1001590697850&parse_mode=HTML&disable_web_page_preview=1&text=" + urllib.parse.quote(
                    "<b>BIS</b>\n"+email + " (#pin__" + pincode + ")\n" + title + "\n<b>" + str(total) +" Rs.</b>\n"+ lid + "\n\n" + phonepeurl)
                if os.name == "nt":
                    try:
                        # proxies = {"http://": "http://127.0.0.1:8888", "https://": "http://127.0.0.1:8888"}
                        proxies = {}
                        r = httpx.get(myurl, headers=d_shuffled, verify=False, proxies=proxies)
                    except Exception as e:
                        r = httpx.get(myurl, headers=d_shuffled, verify=False, proxies=proxies)
                else:
                    try:
                        r = requests.get(myurl, headers=d_shuffled, verify=False, impersonate="chrome110")
                    except:
                        r = requests.get(myurl, headers=d_shuffled, verify=False, impersonate="chrome110")




    except Exception as e:
        print("checkout:\n" + str(e) + " : " + lid)
        logger.error("checkout:" +"\r\n" + lid + "\r\n" + str(e))
        logger.error(traceback.format_exc())
        return False
    return html

def startcheckout(filename, lid, pid):
    print("starting checkout")
    with open(filename) as ff:
        html = ff.read()
    jsonarray = json.loads(html)
    threads = []

    for i in range(1, 20):
        try:
            cookie = jsonarray.get(str(i), None)
            if cookie == None:
                continue
            process = Thread(target=docheckout, args=[
                lid, pid, 1, cookie])
            process.daemon = True
            process.start()
            threads.append(process)
        except:
            pass


    for process in threads:
        process.join()

    print("checkout finished")

#docheckout("LSTSMWGFZ85BHG4BFDJEVWSCV","SMWGFZ85BHG4BFDJ",1,"Network-Type=4g; T=clvs6c6re0l5u1ifk4v6a9o4h-BR1714831397690; vh=730; vw=1536; dpr=1.25; _pxvid=1033d60b-0a1f-11ef-a806-9f4fb150071a; ULSN=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJjb29raWUiLCJhdWQiOiJmbGlwa2FydCIsImlzcyI6ImF1dGguZmxpcGthcnQuY29tIiwiY2xhaW1zIjp7ImdlbiI6IjIiLCJ1bmlxdWVJZCI6IlVVSTI0MDUwNDE5MzQxMTM0NkdBVzdaREIiLCJma0RldiI6bnVsbH0sImV4cCI6MTczMjc1NTAwMywiaWF0IjoxNzE2OTc1MDAzLCJqdGkiOiI5MmVjY2QxYi0wNjJhLTQyOWYtYmFhZi05MmE5NjZiMTlmMWQifQ.-RyChBLrdXhnGTjcDKReSWVKGv0pyHXpqMOMC3FkjQ4; at=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IjhlM2ZhMGE3LTJmZDMtNGNiMi05MWRjLTZlNTMxOGU1YTkxZiJ9.eyJleHAiOjE3MTg5MTA0MDIsImlhdCI6MTcxODkwODYwMiwiaXNzIjoia2V2bGFyIiwianRpIjoiN2NkM2E2ODAtZjJlOC00Nzk0LWJhMjUtMGQzYTNkZjFjNGRmIiwidHlwZSI6IkFUIiwiZElkIjoiY2x2czZjNnJlMGw1dTFpZms0djZhOW80aC1CUjE3MTQ4MzEzOTc2OTAiLCJiSWQiOiJFRkRVSEMiLCJrZXZJZCI6IlZJRTM4RDRDNEIwMEU4NDlCMUI2N0Q4MDY3MEY1MkYyMzEiLCJ0SWQiOiJtYXBpIiwiZWFJZCI6Imk2d0pPenhSTEpMNkxQU2NFcFNXekNaVVlJbE1rZE1NQlZhR29EelpoSjQ3LTVkcWR1YUIyZz09IiwidnMiOiJMSSIsInoiOiJDSCIsIm0iOnRydWUsImdlbiI6NH0.QF-Zg5nvUWMLt7iWilmS2Y8-hCR94DLBSJIt1GVawLU; rt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IjNhNzdlZTgxLTRjNWYtNGU5Ni04ZmRlLWM3YWMyYjVlOTA1NSJ9.eyJleHAiOjE3MzQ3MTk4MDIsImlhdCI6MTcxODkwODYwMiwiaXNzIjoia2V2bGFyIiwianRpIjoiZmFjYmRkYWYtMjIwNi00ODU4LWExM2ItODEwMmY2NWVjNzYyIiwidHlwZSI6IlJUIiwiZElkIjoiY2x2czZjNnJlMGw1dTFpZms0djZhOW80aC1CUjE3MTQ4MzEzOTc2OTAiLCJiSWQiOiJFRkRVSEMiLCJrZXZJZCI6IlZJRTM4RDRDNEIwMEU4NDlCMUI2N0Q4MDY3MEY1MkYyMzEiLCJ0SWQiOiJtYXBpIiwibSI6eyJ0eXBlIjoibiJ9LCJ2IjoiNlo1SkZVIn0.KzjW0BoBoR_ZjQN-cduZjVBZIFP-_s4iCBPGLCxld4c; K-ACTION=null; AMCV_17EB401053DAF4840A490D4C%40AdobeOrg=-227196251%7CMCIDTS%7C19895%7CMCMID%7C56631201953755040380361825201414955784%7CMCAAMLH-1719513437%7C12%7CMCAAMB-1719513437%7C6G1ynYcLPuiQxYZrsz_pkqfLG9yMXBpb2zX5dvJdYQJzPXImdj0y%7CMCOPTOUT-1718915837s%7CNONE%7CMCAID%7CNONE; gpv_pn=HomePage; gpv_pn_t=FLIPKART%3AHomePage; vd=VIE38D4C4B00E849B1B67D80670F52F231-1716974491335-2.1718909978.1718908602.153220921; Network-Type=4g; pxcts=408e0ec9-2f37-11ef-87b2-ddc9798bf220;  SN=VIE38D4C4B00E849B1B67D80670F52F231.TOKA5E4CB797EEB4C31860E629EF6821145.1718909986.LI; _px3=363d1f7bbf2bb660cde2d3cde53f0de324d77ae6ac7e1fdac4deea28940b97b1:ORU4Mk9q5EfE+OLvB7cvCKEVn19t7qYKdhKiqg8NLK\/IjXpEsvucU\/8DJ1VtefEGOl+ytOhN3HO7uigGRBbbtA==:1000:mhnIhIrgU9iXzny3nJhYWPUF4V4wf43wydv4b5XYa1DYAHllH\/Gx+SG6DQMd9iDfgy\/QRttSdL0oEJWNQe1A\/ZCu7QfvuEUeRuXBtpqxUR1U99jVKY9NMnNGjVCA4JXBa+KLcd7Br36pH+mbpSgHHKjrlrgYWWm+UT0qMhf9E2X34+ZMK+HGwE9pU47c4dxOSlbprzwvj\/ag7dRBbZWTrkFx\/FPPW7S4V+22uNzCeZE=; s_sq=flipkart-prd%3D%2526pid%253Dwww.flipkart.com%25253Acheckout%25253Ainit%2526pidt%253D1%2526oid%253Dfunctionkr%252528%252529%25257B%25257D%2526oidt%253D2%2526ot%253DSUBMIT; S=d1t13PTZVJj8\/EFE\/P08\/aD8AN\/Cel9gIB0uVHyjVtvSocY5dzGfDv\/4Uo3hd++4M9WuYncGdw0fI01Iz7w333WWK9w==; useragent=TW96aWxsYS81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXQvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lLzEyNi4wLjAuMCBTYWZhcmkvNTM3LjM2;")