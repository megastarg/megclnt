import datetime
import random
import logging
import threading
from logging.handlers import RotatingFileHandler
import traceback
from threading import Thread
import os
from os import path
import psutil
import sys
from multiprocessing import Process,Queue
import time
import pytz
from fkrdplog import updatetoserver
import gc
import extrafiles
extrafiles.start()
from main import flipkart_parse
from block import block

logger = logging.getLogger("Rotating Log")
logger.setLevel(logging.ERROR)
handler = RotatingFileHandler("log.txt", maxBytes=10000, backupCount=5)
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
handler.setFormatter(formatter)
logger.addHandler(handler)

foldername = "megaclntw_sale_spl/"
if not os.path.exists(foldername):
    os.mkdir(foldername)


def creation_time(path_to_file):
    current = time.time()
    try:
        diff = current-float(os.path.getctime(path_to_file))
        print(diff)
        return diff
    except Exception as e:
        print(str(e))
        return 0

def pcmemory():
    pid = os.getpid()
    py = psutil.Process(pid)
    memoryUse = py.memory_info()[0] / 2. ** 20  # memory use in GB...I think
    print('memory use:', round(memoryUse, 2), "MB")
    if memoryUse > 300:
        os.system("python " + __file__)
        print("Restarting memory usage exceeds ...........")
        sys.exit()

def dowork():
    arr = []
    res_queue = Queue()

    arr.append(['mob_3k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc&p[]=facets.fulfilled_by%255B%255D%3DFlipkart%2BAssured&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D3000&p[]=facets.type%255B%255D%3DSmartphones&p[]=facets.serviceability[]%3Dfalse'])
    arr.append(['tv_foxkyU6k.txt', 1, False, 'https://www.flipkart.com/televisions/foxsky~brand/pr?sid=ckf%2Cczl&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D6000'])
    arr.append(['tv_foxkyU10k.txt', 1, False, 'https://www.flipkart.com/televisions/foxsky~brand/pr?sid=ckf%2Cczl&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10500'])
    arr.append(['tv_foxkydU6k.txt', 1, False, 'https://www.flipkart.com/televisions/foxsky~brand/pr?sid=ckf%2Cczl&marketplace=FLIPKART&sort=discount&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D6000'])
    arr.append(['tv_foxkynewstU6k.txt', 1, False, 'https://www.flipkart.com/televisions/foxsky~brand/pr?sid=ckf%2Cczl&marketplace=FLIPKART&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D6000'])
    arr.append(['tv_tvU5k.txt', 1, False, 'https://www.flipkart.com/home-entertainment/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D5000'])
    arr.append(['tv_foxyU6k.txt', 1, False, 'https://www.flipkart.com/home-entertainment/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DFoxsky&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D6000'])
    arr.append(['tv_foxyU10k.txt', 1, False, 'https://www.flipkart.com/home-entertainment/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DFoxsky&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10500'])
    arr.append(['tv_newestU1ok.txt', 1, False, 'https://www.flipkart.com/home-entertainment/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DFoxsky&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D7000'])
    arr.append(['tv_du7k.txt', 1, False, 'https://www.flipkart.com/home-entertainment/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=discount&p%5B%5D=facets.brand%255B%255D%3DFoxsky&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D7000'])
    arr.append(['tv_sonyU20k.txt', 1, False, 'https://www.flipkart.com/home-entertainment/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DSONY&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['tv_sonydU20k.txt', 1, False, 'https://www.flipkart.com/home-entertainment/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=discount&p%5B%5D=facets.brand%255B%255D%3DSONY&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['tv_sonnyneU20k.txt', 1, False, 'https://www.flipkart.com/home-entertainment/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DSONY&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['tv_sony30p30k.txt', 1, False, 'https://www.flipkart.com/home-entertainment/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DSONY&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['tv_samsungU11k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DSamsung&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D11000'])
    arr.append(['tv_samsung30pU13k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D13000'])
    arr.append(['tv_samsungdU13k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=discount&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['tv_samsungnU13K.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D13000'])
    arr.append(['tv_lgU11K.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DLG&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D11000'])
    arr.append(['tv_tclU10k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DTCL&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['tv_xiomiU10k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DXIAOMI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['tv_motoU11k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DMOTOROLA&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D11000'])
    arr.append(['tv_thomsonU5k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DThomson&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D5000'])
    arr.append(['tv_realmeU8k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3Drealme%2BTechLife&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D8000'])
    arr.append(['tv_toshibaU11k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DTOSHIBA&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D13000'])
    arr.append(['tv_ifalconU13k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DiFFALCON&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D13000'])
    arr.append(['tv_hisenseU10K.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DHisense&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['tv_philipsU13K.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DPHILIPS&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['tv_mixU5k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DAiwa&p%5B%5D=facets.brand%255B%255D%3DBush&p%5B%5D=facets.brand%255B%255D%3DHUIDI&p%5B%5D=facets.brand%255B%255D%3DLlyod&p%5B%5D=facets.brand%255B%255D%3DMITASHI&p%5B%5D=facets.brand%255B%255D%3DMi&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.brand%255B%255D%3DBESTON&p%5B%5D=facets.brand%255B%255D%3DCompaq&p%5B%5D=facets.brand%255B%255D%3DPanasonic&p%5B%5D=facets.brand%255B%255D%3DVu&p%5B%5D=facets.brand%255B%255D%3DReliance&p%5B%5D=facets.brand%255B%255D%3DLumio&p%5B%5D=facets.brand%255B%255D%3DSansui&p%5B%5D=facets.brand%255B%255D%3DBPL&p%5B%5D=facets.brand%255B%255D%3DFoxsky&p%5B%5D=facets.brand%255B%255D%3DLloyd&p%5B%5D=facets.brand%255B%255D%3DCoocaa&p%5B%5D=facets.brand%255B%255D%3DVW&p%5B%5D=facets.brand%255B%255D%3DUniboom&p%5B%5D=facets.brand%255B%255D%3DONIDA&p%5B%5D=facets.brand%255B%255D%3DKODAK&p%5B%5D=facets.brand%255B%255D%3DBlaupunkt&p%5B%5D=facets.brand%255B%255D%3DAcer&p%5B%5D=facets.brand%255B%255D%3DHaier&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D5000'])
    arr.append(['tv_mix2U6K.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DAiwa&p%5B%5D=facets.brand%255B%255D%3DBush&p%5B%5D=facets.brand%255B%255D%3DHUIDI&p%5B%5D=facets.brand%255B%255D%3DLlyod&p%5B%5D=facets.brand%255B%255D%3DMITASHI&p%5B%5D=facets.brand%255B%255D%3DMi&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.brand%255B%255D%3DBESTON&p%5B%5D=facets.brand%255B%255D%3DCompaq&p%5B%5D=facets.brand%255B%255D%3DPanasonic&p%5B%5D=facets.brand%255B%255D%3DVu&p%5B%5D=facets.brand%255B%255D%3DReliance&p%5B%5D=facets.brand%255B%255D%3DLumio&p%5B%5D=facets.brand%255B%255D%3DSansui&p%5B%5D=facets.brand%255B%255D%3DBPL&p%5B%5D=facets.brand%255B%255D%3DLloyd&p%5B%5D=facets.brand%255B%255D%3DCoocaa&p%5B%5D=facets.brand%255B%255D%3DONIDA&p%5B%5D=facets.brand%255B%255D%3DKODAK&p%5B%5D=facets.brand%255B%255D%3DBlaupunkt&p%5B%5D=facets.brand%255B%255D%3DAcer&p%5B%5D=facets.brand%255B%255D%3DHaier&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D6000'])
    arr.append(['tv_mix3U8k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DAiwa&p%5B%5D=facets.brand%255B%255D%3DBush&p%5B%5D=facets.brand%255B%255D%3DLlyod&p%5B%5D=facets.brand%255B%255D%3DMITASHI&p%5B%5D=facets.brand%255B%255D%3DMi&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.brand%255B%255D%3DBESTON&p%5B%5D=facets.brand%255B%255D%3DCompaq&p%5B%5D=facets.brand%255B%255D%3DPanasonic&p%5B%5D=facets.brand%255B%255D%3DVu&p%5B%5D=facets.brand%255B%255D%3DReliance&p%5B%5D=facets.brand%255B%255D%3DLumio&p%5B%5D=facets.brand%255B%255D%3DSansui&p%5B%5D=facets.brand%255B%255D%3DBPL&p%5B%5D=facets.brand%255B%255D%3DLloyd&p%5B%5D=facets.brand%255B%255D%3DCoocaa&p%5B%5D=facets.brand%255B%255D%3DONIDA&p%5B%5D=facets.brand%255B%255D%3DAcer&p%5B%5D=facets.brand%255B%255D%3DHaier&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D8000'])

    if creation_time(foldername)<1200:
        telegram = "off"
    else:
        telegram = "on"

    threads = []

    for i in arr:
        filename = foldername + i[0]
        force = i[1]
        notassured = i[2]
        url = i[3]
        process = Thread(target=flipkart_parse, args=[filename, telegram, force, url, res_queue, notassured])
        process.setDaemon=True
        process.start()
        threads.append(process)

    process = Thread(target=block, args=[foldername])
    process.start()
    threads.append(process)

    process = Thread(target=updatetoserver, args=[foldername])
    process.start()
    threads.append(process)

    for process in threads:
        process.join()

    print("\nTotal Active Threads : " + str(threading.active_count()))
    print("Telegram = " + telegram)

    gotosleep=False
    with open(foldername.strip("/") + '_output.txt', 'w+') as fall:
        res_queue.put(None)
        while True:
            item = res_queue.get()
            if str(item).find("529 Error")!=-1:
                gotosleep=True
            if item is None:
                break
            fall.write(item + "\r\n")
        fall.write("Telegram = " + telegram)
    res_queue.empty()
    del res_queue
    collected = gc.collect()
    print("Garbage collector: collected", "%d objects." % collected)
    if gotosleep == True:
        print("\n529 errors found. sleep 120 seconds")
        time.sleep(120)

while True:
    try:
        dowork()
    except Exception as e:
        logger.error(str(e))
        logger.error(traceback.format_exc())

    d = datetime.datetime.now(pytz.timezone("Asia/Kolkata"))
    hour = d.hour
    if hour >= 3 and hour <= 7:
        sleep = random.randint(5, 10)
        print("----------------------- Sleeping for " + str(sleep) + " Seconds ------------------------")
        time.sleep(sleep)
    else:
        sleep = random.randint(2, 3)
        print("----------------------- Sleeping for " + str(sleep) + " Seconds ------------------------")
        time.sleep(sleep)