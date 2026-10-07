import signal
import subprocess
import sys
import threading
import time
import requests
import gzip
import re
import json
import datetime
import pytz
import urllib.parse
import random
import ssl
import gc
import asyncio
import aiohttp
import checkout
#import msvcrt

requests.packages.urllib3.disable_warnings()
from bs4 import BeautifulSoup as soup
from multiprocessing.pool import ThreadPool
from urllib.request import Request, urlopen
from threading import Thread
import logging
from logging.handlers import RotatingFileHandler
import traceback
from os import system
import os
if os.name=="ntas":
    import httpx
else:
    from curl_cffi import requests
system("title " + "BIS 2 main2links")

#bis_main2links
scriptname = "bis__Flipkart_main2links_local"
telegram_bis_rdplog = True
ssl._create_default_https_context = ssl._create_unverified_context

logger = logging.getLogger("Rotating Log")
logger.setLevel(logging.ERROR)
handler = RotatingFileHandler("log.txt", maxBytes=10000, backupCount=5)
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
handler.setFormatter(formatter)
logger.addHandler(handler)

'''
http_proxy = "http://127.0.0.1:8888"
https_proxy = "https://127.0.0.1:8888"

proxyDict = {
    "http": http_proxy,
    "https": https_proxy,
}

proxy_support = urllib.request.ProxyHandler(proxyDict)
opener = urllib.request.build_opener(proxy_support)
urllib.request.install_opener(opener)
'''

def myasyncsend(telegramurl, header):
    try:
        asyncio.run(async_request(telegramurl, headers=header, proxies="", timeout=15, verify=False))
    except:
        return

async def async_request(url, headers, proxies, timeout, verify):
    async with aiohttp.ClientSession(headers=headers) as session:
        async with session.get(url, proxy=proxies, timeout=timeout, ssl=verify) as resp:
            response = await resp.text()
            if response.find("Bad Request") != -1:
                logger.error(url + "\r\n" + response)
            print(response)


def loadsite(url):
    try:
        data = '{"pageUri":"'+url+'","pageContext":{"pageHashKey":null,"slotContextMap":null,"paginationContextMap":null,"paginatedFetch":false,"pageNumber":1,"fetchAllPages":false,"networkSpeed":252,"trackingContext":null,"fetchSeoData":false},"locationContext":null,"requestContext":null}'
        binary_data = data.encode('utf-8')

        myurl = "https://www.flipkart.com/api/4/page/fetch"

        useragent = random_useragent(open("agent6.txt", "r+"))
        useragent = useragent.replace('Dalvik/2.1.0',
                                      'Mozilla/5.0') + ' AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Mobile Safari/537.36FKUA/msite/0.0.3/msite/Mobile'

        with open("cookie.txt") as fff:
            cokie=fff.readlines()
            sn=cokie[0].strip()
            securecoki = cokie[1].strip()

        header={"User-Agent":"okhttp/4.9.2",
                "Accept-Language":"en-GB,en;q=0.9", "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
                "Accept-Encoding":"gzip",
                "Origin": "https://www.flipkart.com",
                "Referer": "https://www.flipkart.com",
                "Content-Type":"application/json; charset=UTF-8",
                "X-User-Agent":useragent,
                "sn":sn,
                "secureCookie":securecoki
                }

        l = list(header.items())
        random.shuffle(l)
        d_shuffled = dict(l)


        if os.name=="ntas":
            try:
                proxies = {"http://": "http://127.0.0.1:8888", "https://": "http://127.0.0.1:8888"}
                proxies = {}
                r = httpx.post(myurl, data=data, headers=d_shuffled, verify=False, proxies=proxies)
            except Exception as e:
                r = httpx.post(myurl, data=data, headers=d_shuffled, verify=False, proxies=proxies)
        else:
            try:
                r = requests.post(myurl, data=data, headers=d_shuffled, verify=False, impersonate="chrome116")
            except:
                r = requests.post(myurl, data=data, headers=d_shuffled, verify=False, impersonate="chrome110")

        html=r.text
        if r.status_code==529:
            print("error 529. sleep 120 secs")
            time.sleep(120)
    except Exception as e:
        print("loadsite:\n" + str(e) + " : " + url)
        logger.error("loadsite:" +"\r\n" + url + "\r\n" + str(e))
        logger.error(traceback.format_exc())
        return False
    return html

def loadsellers(pid):
    try:
        data = '{"requestContext":{"productId":"' + pid + '"},"locationContext":{}}'
        binary_data = data.encode('utf-8')

        myurl = "https://www.flipkart.com/api/3/page/dynamic/product-sellers"

        useragent = random_useragent(open("agent6.txt", "r+"))
        useragent = useragent.replace('Dalvik/2.1.0',
                                      'Mozilla/5.0') + ' AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Mobile Safari/537.36FKUA/msite/0.0.3/msite/Mobile'

        with open("cookie.txt") as fff:
            cokie=fff.readlines()
            sn=cokie[0].strip()
            securecoki = cokie[1].strip()

        header={"User-Agent":"okhttp/4.9.2",
                "Accept-Language":"en-GB,en;q=0.9", "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
                "Accept-Encoding":"gzip",
                "Origin": "https://www.flipkart.com",
                "Referer": "https://www.flipkart.com",
                "Content-Type":"application/json; charset=UTF-8",
                "X-User-Agent":useragent,
                "sn":sn,
                "secureCookie":securecoki
                }

        l = list(header.items())
        random.shuffle(l)
        d_shuffled = dict(l)

        if os.name=="ntas":
            try:
                proxies = {"http://": "http://127.0.0.1:8888", "https://": "http://127.0.0.1:8888"}
                proxies = {}
                r = httpx.post(myurl, data=data, headers=d_shuffled, verify=False, proxies=proxies)
            except Exception as e:
                r = httpx.post(myurl, data=data, headers=d_shuffled, verify=False, proxies=proxies)
        else:
            try:
                r = requests.post(myurl, data=data, headers=d_shuffled, verify=False, impersonate="chrome110")
            except:
                r = requests.post(myurl, data=data, headers=d_shuffled, verify=False, impersonate="chrome110")

        html=r.text

        if html.find("recaptcha")!=-1:
            print("recaptcha")
            return

        if r.status_code>=300:
            print("seller page: "+ str(r.status_code))
            return False
    except Exception as e:
        print("loadsellers:\n" + str(e) + " : " + pid)
        logger.error("loadsellers:" +"\r\n" + pid + "\r\n" + str(e))
        logger.error(traceback.format_exc())
        return False
    return html

def sellerspage(url, minprice, maxprice, qt, phonepe, checkouton):
    try:
        global scriptname
        urls = loadlinks("bis")
        try:
            res = re.search("pid=(.*?)(?=&|$)", url)
            pid = res[1]
        except Exception as e:
            pid = ""

        lowestprice = 9999999
        lowestseller = "None"
        lowestlid = "None"
        try:
            html = loadsellers(pid)
            if html==False:
                return False
            jsonarray = json.loads(html)
            try:
                title = jsonarray["RESPONSE"]["pageContext"]["titles"]["title"]
                jsondata = jsonarray["RESPONSE"]["data"]["product_seller_detail_1"]["data"]
                status = jsonarray["RESPONSE"]["pageData"]["pageContext"]["trackingDataV2"].get("productStatus", "IN_STOCK")
            except:
                return False

            d = datetime.datetime.now(pytz.timezone("Asia/Kolkata"))
            currenttime = d.strftime("%H:%M:%S")

            for sellers in jsondata:
                listingid = sellers["value"]["listingId"]
                seller = sellers["value"]["sellerInfo"]["value"]["name"]
                price = sellers["value"]["pricing"]["value"]["finalPrice"]["decimalValue"]

                if float(price) < float(lowestprice):
                    lowestprice = price
                    lowestseller = seller
                    lowestlid = listingid

                try:
                    maxorderqtyavailable = jsonarray["RESPONSE"]["data"]["product_seller_detail_1"]["data"][0]["value"]["npsListing"]["promise"]["availability"]["maxQuantity"]
                    if maxorderqtyavailable == None:
                        res = re.search("Hurry, Only (.*?) left", html)
                        maxorderqtyavailable = res[1].strip()
                    minorderqty = jsonarray["RESPONSE"]["data"]["product_seller_detail_1"]["data"][0]["value"]["npsListing"]["minimum_order_quantity"]
                    if minorderqty == None:
                        minorderqty = jsonarray["RESPONSE"]["data"]["product_seller_detail_1"]["data"][0]["value"]["actions"]["ADD_TO_CART"]["data"][0]["value"]["quantityMetaData"]["minQuantity"]

                    if maxorderqtyavailable!=None and minorderqty!=None and float(maxorderqtyavailable)<float(minorderqty):
                        print(str(currenttime) + " = " + lowestseller + " [" + str(maxorderqtyavailable) + " < " + str(minorderqty) + "]" + " -> " + lowestprice + " -> " + lowestlid + " -> " + title)
                        return False
                except Exception as e:
                    pass

            header={"User-Agent":"okhttp/4.9.2",
                    "Accept-Language":"en-GB,en;q=0.9", "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
                    "Accept-Encoding":"gzip",
                    }
            print(str(currenttime) + " = " + lowestseller + " [" + pid + "]" + " -> " + lowestprice + " -> " + lowestlid + " -> " + title)
            if float(lowestprice)>float(minprice) and float(lowestprice)<float(maxprice):
                msg = urllib.parse.quote("<b>URGENT_s :- BIS("+scriptname+")</b>\n" + status + "\n" + lowestseller + "\n" + lowestprice + " Rs.\n\n" + url + "&lid=" + lowestlid)
                telegramurl = "https://api.telegram.org/bot5595946728:AAE04_hy37h-SNUAKgC9nrUOjr-c-513Tm8/sendMessage?chat_id=-4629487139&parse_mode=HTML&disable_web_page_preview=1&text=" + msg
                #r = requests.get(telegramurl, headers=header, proxies={"http": "", "https": ""}, timeout=15, verify=False)
                try:
                    myasyncsend(telegramurl, header)
                except:
                    pass

                if checkouton != "nocheckout":
                    params = []
                    for z in urls:
                        params.append([z, lowestlid, minprice, maxprice, qt, phonepe, checkouton])
                    pool = ThreadPool(10)
                    results = pool.imap_unordered(fetch_url, params)
                    for url, html, error in results:
                        if error is None:
                            print("%r fetched" % (url))
                        else:
                            print("error %r fetched" % (url))
                    pool.terminate()
                    pool.join()

        except Exception as e:
            print(str(e))
            traceback.print_exc()
        gc.collect()
        return False
    except Exception as e:
        print("sellers_page:\n" + pid + "\n" + str(e))
        logger.error("sellers_page:\n" + pid + "\r\n" + url + str(e))
        logger.error(traceback.format_exc())
        return False

def getsellerdetails(name,jsonarray):
    try:
        rating = None
        slots = jsonarray["RESPONSE"]["slots"]
        for slot in slots:
            try:
                sellername = slot["widget"]["data"]["SellerMetaValue"]["value"].get("name","")
                if sellername == name:
                    rating = slot["widget"]["data"]["rating"]["value"].get("average","")
                    return rating
            except:
                pass
        return rating
    except Exception as e:
        print(str(e))
        logger.error("getsellerdetails:\r\n" + str(e))
        logger.error(traceback.format_exc())
        return None

def checkstatus(url, minprice, maxprice, qt, phonepe, checkouton):
    try:
        global scriptname
        urls = loadlinks("bis")
        try:
            res = re.search("lid=(.*?)(?=&|$)", url)
            listingid = res[1]
        except Exception as e:
            listingid = ""

        try:
            html = loadsite(url)
            if html.find("\"STATUS_CODE\":500")!=-1:
                html = loadsite(url)
            jsonarray = json.loads(html)

            #print(getsellerdetails("",jsonarray))            

            try:
                title = jsonarray["RESPONSE"]["pageData"]["pageContext"]["titles"].get("title", listingid) + " " + jsonarray["RESPONSE"]["pageData"]["pageContext"]["titles"].get("subtitle", "") + " " + jsonarray["RESPONSE"]["pageData"]["pageContext"]["titles"].get("coSubtitle", "")
            except:
                ras=re.search("\"prependingText\":\"(.*?)\"",html)
                title=ras[1]
                #title = jsonarray["RESPONSE"]["slots"][2]["widget"]["data"]["dlsData"]["customEllipsisData_0"]["value"].get("prependingText", listingid)
            lid = jsonarray["RESPONSE"]["pageData"]["pageContext"].get("listingId", "")
            sellername = jsonarray["RESPONSE"]["pageData"]["pageContext"]["trackingDataV2"].get("sellerName", "")

        except:
            return False

        if listingid == "":
            listingid = url
        try:
            status = jsonarray["RESPONSE"]["pageData"]["pageContext"]["fdpEventTracking"]["events"]["psi"]["pls"].get("availabilityStatus", "IN_STOCK")
        except:
            status = jsonarray["RESPONSE"]["pageData"]["pageContext"]["trackingDataV2"].get("productStatus", "IN_STOCK")

        if status == "ready" or status=="COMING_SOON" or status=="OUT_OF_STOCK":
            print(status + " = " + listingid + " -> " + "unavailable" + " -> " + title)
            return False

        try:
            price = jsonarray["RESPONSE"]["pageData"]["pageContext"]["pricing"]["finalPrice"].get("decimalValue", 999999)
        except:
            price = str(jsonarray["RESPONSE"]["pageData"]["pageContext"]["fdpEventTracking"]["events"]["psi"]["ppd"].get("finalPrice", 999999))
        d = datetime.datetime.now(pytz.timezone("Asia/Kolkata"))
        currenttime = d.strftime("%H:%M:%S")
        print(str(currenttime) + " => " + status + " = " + listingid + " -> " + price + " -> " + title)

        if status.lower().find("OUT_OF_STOCK") != -1 or status.lower().find("out of stock") != -1:
            return True
        elif status.lower().find("OUT_OF_STOCK") == -1 and status.lower().find("out of stock") == -1 and lid != listingid:
            return True
        else:
            #changed listingid to lid
            header = {}
            header.update([("accept-language", "en-US,en;q=0.9")])
            header.update([("accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3")])
            header.update([("accept-encoding", "gzip, deflate")])
            header.update([("User-Agent", "Mozilla/5.0 (Linux; Android 8.1.0; ASUS_X00TD Build/OPM1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/102.0.5005.78 Mobile Safari/537.36")])

            if float(price) <= float(maxprice):
                print("----------------- "+listingid + " --- "+price+" ------ "+status+" -----------")
                msg = urllib.parse.quote("<b>URGENT_c :- BIS("+scriptname+")</b>\n"+status+"\n"+lid+"\n"+price+"\n\n"+sellername+"\n\n"+url)
                telegramurl = "https://api.telegram.org/bot5595946728:AAE04_hy37h-SNUAKgC9nrUOjr-c-513Tm8/sendMessage?chat_id=-4629487139&parse_mode=HTML&disable_web_page_preview=1&text=" + msg
                #r = requests.get(telegramurl, headers=header, proxies={"http": "", "https": ""}, timeout=15, verify=False)
                try:
                    myasyncsend(telegramurl, header)
                except:
                    pass

                if checkouton != "nocheckout":
                    params = []
                    for z in urls:
                        params.append([z, lid, minprice, maxprice, qt, phonepe, checkouton])
                    pool = ThreadPool(10)
                    results = pool.imap_unordered(fetch_url, params)
                    for url, html, error in results:
                        if error is None:
                            print("%r fetched" % (url))
                        else:
                            print("error %r fetched" % (url))
                    pool.terminate()
                    pool.join()
            gc.collect()
            return False
    except Exception as e:
        print("Check_Status:\n" + url + "\n" + str(e))
        logger.error("Check_Status:\r\n" + listingid + "\r\n" + url + str(e) +"\r\nhtml=" + html)
        logger.error(traceback.format_exc())
        return False

def start():
    try:
        products = []
        if os.path.exists("bis.txt") == False:
            open("bis.txt", "w").close

        with open("bis.txt", "r") as ff:
            lines = ff.readlines()
            if lines.count==0:
                print("0 lines found")
            for line in lines:
                if line[0] == "#" or line == "\n" or line == "\r\n":
                    continue
                parts = line.split(",")
                producturl = parts[0]
                qt = parts[1]
                minprice = parts[2]
                maxprice = parts[3]
                refreshtime = parts[4]
                phonepe = parts[5]
                checkouton = parts[6]

                try:
                    desc = parts[7]
                except Exception as e:
                    desc = "bis"
                try:
                    timestamp = parts[8]
                except Exception as e:
                    timestamp = 9999999999
                products.append([producturl,qt,minprice,maxprice,refreshtime,phonepe,checkouton,desc,timestamp])

        threads = []

        for i in products:
            producturl = i[0]
            qt = i[1]
            minprice = i[2]
            maxprice = i[3]
            refresh = i[4]
            phonepe = i[5]
            checkouton = i[6]

            try:
                process = Thread(target=loopstart, args=[producturl, qt, minprice, maxprice, refresh, phonepe, checkouton])
                process.setDaemon(True)
                process.start()
                threads.append(process)

            except Exception as e:
                logger.error(str(e))
                logger.error(traceback.format_exc())

        global telegram_bis_rdplog
        if telegram_bis_rdplog == True:
            process1 = Thread(target=telegram_newbis, args=[])
            process1.setDaemon(True)
            process1.start()
            threads.append(process1)

            #process2 = Thread(target=rdplog, args=[])
            #process2.setDaemon(True)
            #process2.start()
            #threads.append(process2)


        for process in threads:
            process.join()

        print("\nTotal Active Threads : " + str(threading.active_count()))
    except Exception as e:
        print("start:\n" + str(e))
        logger.error("start:\r\n" + str(e))
        logger.error(traceback.format_exc())
        return False

def random_useragent(afile):
    try:
        line = next(afile)
        for num, aline in enumerate(afile, 2):
            if random.randrange(num):
                continue
            line = aline

        list = line.split("->")
        return list[2].strip()
    except Exception as e:
        print("random_useragent:\n" + str(e))
        logger.error("random_useragent:\r\n" + str(e))
        logger.error(traceback.format_exc())
        return False

def newcookie1():
    try:
        date = datetime.datetime.now().strftime("%d-%m-%Y")
        try:
            with open('cookie.txt', 'r+') as f:
                writtencookie = f.read().strip().split("\n")
                date1 = writtencookie.pop(0)
                if date == date1:
                    return
        except FileNotFoundError:
            pass

        f = open("503.txt", "w+", encoding="utf-8")
        f.write(date + "\n")
        f.close()

        cookie = date + "\n"

        for counter in range(1,5):
            cookie1 = ""
            uclient = Request(
                "https://www.amazon.in/gp/aw/s?bbn=1968024031&rh=n%3A1571271031%2Cn%3A%211571272031%2Cn%3A1968024031%2Cp_n_pct-off-with-tax%3A90-%2Cp_85%3A10440599031%2Cp_36%3A4595084031&s=price-asc-rank&dc&fst=as%3Aoff&cid=08e6b9c8bdfc91895ce634a035f3d00febd36433&format=json&dataVersion=v0.2&page=1")
            uclient.add_header("User-Agent",
                               "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36")
            uclient.add_header("Accept", "*/*")
            uclient.add_header("Accept-Language", "en-GB,en-US;q=0.9,en;q=0.8")
            uclient.add_header("Connection", "keep-alive")
            response = urlopen(uclient, timeout=120)
            output = response.info()
            for k, l in output.items():
                if k.lower() == "set-cookie":
                    ab = re.search('(.*?);', l)
                    cookie1 += ab[1] + "; "
            uclient.add_header("cookie", cookie1)
            response = urlopen(uclient, timeout=120)
            output = response.info()
            for k, l in output.items():
                if k.lower() == "set-cookie":
                    ab = re.search('(.*?);', l)
                    cookie1 += ab[1] + "; "
            cookie += cookie1 + "x-amz-captcha-1=1614404126209648; x-amz-captcha-2=8VE4Sm76pqj607P6uBZj/A==;" + "\n"
            print(str(counter) + " cookie added. please wait....")

        f = open("cookie.txt", "w+", encoding="utf-8")
        while True:
            try:
                #msvcrt.locking(f.fileno(), msvcrt.LK_NBLCK,1)
                f.write(cookie.strip())
                #print("writing cookie:-" + cookie.strip())
                break
            except IOError as e:
                time.sleep(0.1)
        #msvcrt.locking(f.fileno(), msvcrt.LK_UNLCK,1)
        f.close()
        return
    except Exception as e:
        print(str(e))
        return

def fetch_url(params):
    try:
        url = params[0]
        lid = params[1]
        minprice = params[2]
        maxprice = params[3]
        qt = params[4]
        phonepe = params[5]
        checkouton = "all" if len(params)<7 else params[6]

        url = re.sub('&qt=(.*?)&', '&', url)
        url = re.sub('&phonepe=(.*?)&', '&', url)

        print("going to checkout: " + url + "&i=" + str(round(float(minprice)) + 10) + "&maxprice=" + str(round(float(maxprice)) + 10) + "&itemid=" + lid + "&qt=" + qt + "&phonepe=" + phonepe)
        if lid.find("pid=") != -1:
            uclient = Request(url + "&i=" + str(round(float(minprice)) + 10) + "&maxprice=" + str(round(float(maxprice)) + 10) + "&url=" + lid + "&qt=" + qt + "&phonepe=" + phonepe + "&filename=bis&checkouton="+checkouton)
        else:
            uclient = Request(url + "&i=" + str(round(float(minprice)) + 10) + "&maxprice=" + str(round(float(maxprice)) + 10) + "&itemid=" + lid + "&qt=" + qt + "&phonepe=" + phonepe + "&filename=bis&checkouton="+checkouton)

        uclient.add_header("User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36")
        uclient.add_header("Accept", "*/*")
        uclient.add_header("Accept-Language", "en-GB,en-US;q=0.9,en;q=0.8")
        uclient.add_header("Connection", "keep-alive")
        response = urlopen(uclient, timeout=120)

        if response.info().get('Content-Encoding') == 'gzip':
            html = gzip.decompress(response.read()).decode('utf8')
        elif response.info().get('Content-Encoding') == 'deflate':
            html = response.read().decode('utf8')
        else:
            html = response.read().decode('utf8')
        return url + " " + lid, html, None
    except Exception as e:
        logger.error(url + "&j=" + str(round(float(maxprice)) + 10) + "&itemid=" + lid + str(e))
        logger.error(traceback.format_exc())
        return lid, None, e

def loopstart(producturl, qt, minprice, maxprice, refresh, phonepe, checkouton):
    while(1):
        try:
            if producturl.find("lid=") != -1:
                checkstatus(producturl, minprice, maxprice, qt, phonepe, checkouton)
            else:
                sellerspage(producturl, minprice, maxprice, qt, phonepe, checkouton)
            gc.collect()
            time.sleep(int(refresh))
        except:
            gc.collect()
            pass

def loadlinks(data):
    try:
        with open("main2links.txt") as f_in:
            jsonarray = json.loads(f_in.read())
            url = jsonarray[data]
            return url
    except Exception as e:
        logger.error("loadlinks" + str(e))
        logger.error(traceback.format_exc())
        return None

def getipinfo():
    try:
        ip_details = {}
        try:
            response = requests.get(
                "https://ip-info.ff.avast.com/v2/info").content.decode()
            jsondata = json.loads(response)
            ip_details["ip"] = jsondata["ip"]
            ip_details["country"] = jsondata["country"]
            ip_details["city"] = jsondata["city"]
        except Exception as e:
            try:
                response = requests.get(
                    "https://napps-2.com/v1/helpers/ips/insights").content.decode()
                jsondata = json.loads(response)
                ip_details["ip"] = jsondata["ip"]
                ip_details["country"] = jsondata["country"]
                ip_details["city"] = jsondata["city"]
            except Exception as e:
                try:
                    response = requests.get(
                        "http://ipwho.is/").content.decode()
                    jsondata = json.loads(response)
                    ip_details["ip"] = jsondata["ip"]
                    ip_details["country"] = jsondata["country_code"]
                    ip_details["city"] = jsondata["city"]
                except Exception as e:
                    response = requests.get(
                        "http://api.db-ip.com/v2/free/self/").content.decode()
                    jsondata = json.loads(response)
                    ip_details["ip"] = jsondata["ipAddress"]
                    ip_details["country"] = jsondata["countryCode"]
                    ip_details["city"] = jsondata["city"]
                #response = requests.get("http://ip-api.com/json/").content.decode()

        return ip_details
    except Exception as e:
        print("IP fetch Exception")
        print(str(e))
        return None

def reboot():
    os.system("reboot")
    return

def getlastupdate():
    try:
        timezone = pytz.timezone("Asia/Kolkata")
        dateTime = datetime.datetime.fromtimestamp(time.time()).astimezone(timezone).strftime('%d-%m-%y %H:%M')
        return dateTime
    except Exception as e:
        print(str(e))
        return None

def rdplog():
    while(1):
        try:
            time.sleep(180)
            global scriptname

            data = {
                "server": scriptname,
                "ip": getipinfo(),
                "modification_time": getlastupdate(),
                "consumed_time": 0
            }

            post = json.dumps(data)
            print(post)
            url = "http://rdptv.lalkothi.tech/rdplog/update1.php"
            response = requests.post(url, data=post).content.decode()

            if response.find("updatefile") != -1:
                jsondata = json.loads(response)
                link = jsondata["link"]
                path = jsondata["pathname"]
                r = requests.get(link)
                with open(path, 'wb') as f:
                    f.write(r.content)
            if response.find("\"reboot\":\"yes\"") != -1:
                reboot()
            continue
        except Exception as e:
            print(str(e))
            continue



def telegram_newbis():
    oldmsgid = ""
    while(1):
        time.sleep(10)
        try:
            filename = "bis.txt"
            try:
                f = open(filename, "r+")
                get_contents = f.read()
                f.close()
            except FileNotFoundError:
                f = open(filename, "w+")
                get_contents = f.read()
                f.close()

            try:
                url = "https://api.telegram.org/bot5595946728:AAE04_hy37h-SNUAKgC9nrUOjr-c-513Tm8/getUpdates?offset=-1"

                uclient = Request(url)

                uclient.add_header(
                    "User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36")
                uclient.add_header("Accept", "*/*")
                uclient.add_header("Accept-Language", "en-GB,en-US;q=0.9,en;q=0.8")
                uclient.add_header("Accept-Encoding", "gzip, deflate")
                uclient.add_header("Connection", "keep-alive")
                response = urlopen(uclient, timeout=120)

                if response.info().get('Content-Encoding') == 'gzip':
                    html = gzip.decompress(response.read()).decode('utf8')
                elif response.info().get('Content-Encoding') == 'deflate':
                    html = response.read().decode('utf8')
                else:
                    html = response.read().decode('utf8')
            except Exception as e:
                print(str(e))
                logger.error("telegram_bisload1" + str(e))
                logger.error(traceback.format_exc())
                continue

            messages = json.loads(html)
            for val2 in messages["result"]:
                try:
                    text = val2["message"].get("text", "")
                    msgid = val2["message"].get("message_id", "")
                    if oldmsgid==msgid:
                        break
                    else:
                        oldmsgid=msgid
                    user = val2["message"]["from"].get("username", "")
                    userid = val2["message"]["from"].get("id", "")
                    if (text.count(",")<2 and text.lower()!="delete") and text.lower().find("list ")==-1:
                        continue
                    if text.lower()=="delete":
                        print("\ndelete received from telegram\n")
                        anydeleted=False
                        repliedtext = val2["message"]["reply_to_message"].get("text", "")
                        parts = repliedtext.split(",")
                        producturl = parts[0]
                        #maxprice = parts[1]
                        #refreshtime = parts[2]
                        #checkouton = parts[3]
                        with open(filename, 'r') as ff:
                            alllines = ff.readlines()
                        mod_alllines = list()
                        for line in alllines:
                            if line.find(producturl)==-1 and line!="\r\n" and line!="\n":
                                mod_alllines.append(line)
                            else:
                                anydeleted = True

                        if anydeleted == True:
                            with open(filename, 'w+') as ff:
                                for line in mod_alllines:
                                    ff.write(f"{line}")

                            url = "https://api.telegram.org/bot5595946728:AAE04_hy37h-SNUAKgC9nrUOjr-c-513Tm8/sendMessage?chat_id=-4629487139&disable_notification=1&parse_mode=HTML&text="+urllib.parse.quote("Bis deleted "+ str(user) +": \n\n" + repliedtext + "\n\ncount=" + str(len(mod_alllines)))
                            uclient = Request(url)
                            uclient.add_header(
                                "User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36")
                            uclient.add_header("Accept", "*/*")
                            uclient.add_header("Accept-Language", "en-GB,en-US;q=0.9,en;q=0.8")
                            uclient.add_header("Accept-Encoding", "gzip, deflate")
                            uclient.add_header("Connection", "keep-alive")
                            response = urlopen(uclient, timeout=120)

                            if os.name == 'nt':
                                subprocess.call(["cmd.exe", "/c", "START", "python", __file__])
                            else:
                                os.execv(sys.executable, [sys.executable, __file__] + sys.argv)
                            os.kill(os.getpid(), signal.SIGINT)
                    elif text.lower().find("list ") != -1:
                        print("\n" +text.lower() + " received from telegram\n")
                        #repliedtext = val2["message"]["reply_to_message"].get("text", "")
                        parts = text.strip().split(" ")
                        with open(filename, 'r') as ff:
                            alllines = ff.readlines()
                        listnum = int(parts[1])

                        url = "https://api.telegram.org/bot5595946728:AAE04_hy37h-SNUAKgC9nrUOjr-c-513Tm8/sendMessage?chat_id="+str(userid)+"&disable_notification=1&parse_mode=HTML&text="+urllib.parse.quote(alllines[listnum-1])
                        uclient = Request(url)
                        uclient.add_header(
                            "User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36")
                        uclient.add_header("Accept", "*/*")
                        uclient.add_header("Accept-Language", "en-GB,en-US;q=0.9,en;q=0.8")
                        uclient.add_header("Accept-Encoding", "gzip, deflate")
                        uclient.add_header("Connection", "keep-alive")
                        response = urlopen(uclient, timeout=120)
                    else:
                        parts = text.split(",")
                        producturl = parts[0]
                        maxprice = parts[1]
                        refreshtime = parts[2]
                        checkouton = "all" if len(parts)<4 else parts[3]
                        if get_contents.find(producturl) == -1:
                            print("\nnew bis received from telegram\n")
                            with open("bis.txt", "a+") as ff:
                                bistext = producturl + ",10,0," + str(maxprice) + "," + str(refreshtime) + ",yes,"+checkouton+",telegram,99999999"
                                ff.write("\r\n"+bistext)
                                url = "https://api.telegram.org/bot5595946728:AAE04_hy37h-SNUAKgC9nrUOjr-c-513Tm8/sendMessage?chat_id=-4629487139&disable_notification=1&parse_mode=HTML&text="+urllib.parse.quote("New Bis started by "+ str(user) +": \n\n" + bistext)
                                uclient = Request(url)
                                uclient.add_header(
                                    "User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36")
                                uclient.add_header("Accept", "*/*")
                                uclient.add_header("Accept-Language", "en-GB,en-US;q=0.9,en;q=0.8")
                                uclient.add_header("Accept-Encoding", "gzip, deflate")
                                uclient.add_header("Connection", "keep-alive")
                                response = urlopen(uclient, timeout=120)

                            if os.name == 'nt':
                                subprocess.call(["cmd.exe", "/c", "START", "python", __file__])
                            else:
                                os.execv(sys.executable, [sys.executable, __file__] + sys.argv)
                            os.kill(os.getpid(), signal.SIGINT)
                        '''else:
                            url = "https://api.telegram.org/bot5595946728:AAE04_hy37h-SNUAKgC9nrUOjr-c-513Tm8/sendMessage?chat_id="+str(userid)+"&disable_notification=1&parse_mode=HTML&text="+urllib.parse.quote("Already exists : \n\n" + text)
                            uclient = Request(url)
                            uclient.add_header(
                                "User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36")
                            uclient.add_header("Accept", "*/*")
                            uclient.add_header("Accept-Language", "en-GB,en-US;q=0.9,en;q=0.8")
                            uclient.add_header("Accept-Encoding", "gzip, deflate")
                            uclient.add_header("Connection", "keep-alive")
                            response = urlopen(uclient, timeout=120)'''
                except Exception as e:
                    print(str(e))
                    logger.error("telegram_bisload2" + str(e))
                    logger.error(traceback.format_exc())

        except Exception as e:
            print(str(e))
            logger.error("telegram_bisload0" + str(e))
            logger.error(traceback.format_exc())

if __name__ == '__main__':
    try:
        threads = []

        process1 = Thread(target=start, args=[])
        process1.start()
        threads.append(process1)

        for process in threads:
            process.join()
    except Exception as e:
        print(str(e))
        logger.error("start" + str(e))
        logger.error(traceback.format_exc())


