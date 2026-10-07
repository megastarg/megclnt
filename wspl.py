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
from multiprocessing import Process, Queue
import time
import pytz
from fkrdplog import updatetoserver
import gc
import extrafiles
extrafiles.start()
from main import flipkart_parse
from block import block
from os import system, name

def clear():
    if name == 'nt':
        _ = system('cls')
    else:
        _ = system('clear')

logger = logging.getLogger("Rotating Log")
logger.setLevel(logging.ERROR)
handler = RotatingFileHandler("log.txt", maxBytes=10000, backupCount=5)
formatter = logging.Formatter(
    '%(asctime)s - %(name)s - %(levelname)s - %(message)s')
handler.setFormatter(formatter)
logger.addHandler(handler)

foldername = "wspl_bob/"
if not os.path.exists(foldername):
    os.mkdir(foldername)


def creation_time(path_to_file):
    current = time.time()
    try:
        return current-float(os.path.getctime(path_to_file))
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

    arr.append(['mob_5G_50p.txt', 1, False, 'https://www.flipkart.com/search?sid=tyy%2C4io&otracker=CLP_Filters&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.network_type%255B%255D%3D5.5G&p%5B%5D=facets.network_type%255B%255D%3D5G&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock'])
    arr.append(['mob_smart_50p.txt', 1, False, 'https://www.flipkart.com/search?sid=tyy%2C4io&otracker=CLP_Filters&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.type%255B%255D%3DSmartphones&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock'])
    arr.append(['mob_brand_50p.txt', 1, False, 'https://www.flipkart.com/search?sid=tyy%2C4io&otracker=CLP_Filters&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.brand%255B%255D%3DGoogle&p%5B%5D=facets.brand%255B%255D%3DMOTOROLA&p%5B%5D=facets.brand%255B%255D%3Dvivo&p%5B%5D=facets.brand%255B%255D%3DOPPO&p%5B%5D=facets.brand%255B%255D%3DInfinix&p%5B%5D=facets.brand%255B%255D%3DNothing&p%5B%5D=facets.brand%255B%255D%3DPOCO&p%5B%5D=facets.brand%255B%255D%3Drealme&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.brand%255B%255D%3DOnePlus&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.brand%255B%255D%3DMi&p%5B%5D=facets.brand%255B%255D%3DTecno&p%5B%5D=facets.brand%255B%255D%3DIQOO&p%5B%5D=facets.brand%255B%255D%3DNokia&p%5B%5D=facets.brand%255B%255D%3DHuawei&p%5B%5D=facets.brand%255B%255D%3DCMF%2Bby%2BNothing&p%5B%5D=facets.brand%255B%255D%3DAi%252B&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock'])
    arr.append(['mob_smartnew_50p.txt', 1, False, 'https://www.flipkart.com/mobiles-accessories/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&p%5B%5D=facets.type%255B%255D%3DSmartphones&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_brandnew_50p.txt', 1, False, 'https://www.flipkart.com/search?sid=tyy%2C4io&otracker=CLP_Filters&sort=recency_desc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.fulfilled_by%255B%255D%3DF-Assured&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.brand%255B%255D%3DGoogle&p%5B%5D=facets.brand%255B%255D%3DMOTOROLA&p%5B%5D=facets.brand%255B%255D%3Dvivo&p%5B%5D=facets.brand%255B%255D%3DOPPO&p%5B%5D=facets.brand%255B%255D%3DInfinix&p%5B%5D=facets.brand%255B%255D%3DNothing&p%5B%5D=facets.brand%255B%255D%3DPOCO&p%5B%5D=facets.brand%255B%255D%3Drealme&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.brand%255B%255D%3DOnePlus&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.brand%255B%255D%3DMi&p%5B%5D=facets.brand%255B%255D%3DTecno&p%5B%5D=facets.brand%255B%255D%3DIQOO&p%5B%5D=facets.brand%255B%255D%3DNokia&p%5B%5D=facets.brand%255B%255D%3DHuawei&p%5B%5D=facets.brand%255B%255D%3DCMF%2Bby%2BNothing&p%5B%5D=facets.brand%255B%255D%3DAi%252B&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock'])
    arr.append(['mob_speciality_50p.txt', 1, False, 'https://www.flipkart.com/search?sid=tyy%2C4io&otracker=CLP_Filters&sort=popularity&p%5B%5D=facets.speciality%255B%255D%3DHigher%2BPerformance&p%5B%5D=facets.speciality%255B%255D%3DBig%2BStorage&p%5B%5D=facets.speciality%255B%255D%3DLong-Lasting%2BBattery&p%5B%5D=facets.speciality%255B%255D%3DSelfie%2BCamera&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_5G_8k.txt', 1, False, 'https://www.flipkart.com/search?sid=tyy%2C4io&otracker=CLP_Filters&sort=price_asc&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&p%5B%5D=facets.network_type%255B%255D%3D5.5G&p%5B%5D=facets.network_type%255B%255D%3D5G&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D8000'])
    arr.append(['mob_brandsel_23k.txt', 1, False, 'https://www.flipkart.com/search?sid=tyy%2C4io&otracker=CLP_Filters&sort=price_asc&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.brand%255B%255D%3DGoogle&p%5B%5D=facets.brand%255B%255D%3DNothing&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D23000'])
    arr.append(['mob_apple_u40k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&p%5B%5D=facets.brand%255B%255D%3DApple&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['mob_newest_u40k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&p%5B%5D=facets.brand%255B%255D%3DApple&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['mob_50pc_u40k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['mob_50pc.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_splprice_u40k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.offer_type%255B%255D%3DSpecial%2BPrice&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['mob_40p_40k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['mob_samsung_u50pc.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&sort=price_asc'])
    arr.append(['mob_50pu30k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_newest_50p.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DSamsung&sort=recency_desc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_50p30k_newest.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.internal_storage%255B%255D%3D256%2BGB%2B%2526%2BAbove&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_nothing_u20k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DNothing&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_nothing_newest_u20k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DNothing&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_nothing_30p.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DNothing&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=price_asc'])
    arr.append(['mob_nothing_30p_20k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000&p%5B%5D=facets.brand%255B%255D%3DNothing'])
    arr.append(['mob_google_u30k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DGoogle&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_google_20p.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DGoogle&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D20%2525%2Bor%2Bmore'])
    arr.append(['mob_google_20p_30k.txt', 1, False, 'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D20%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DGoogle&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['lap_core_processor_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_desc&p%5B%5D=facets.processor%255B%255D%3DCore%2B3&p%5B%5D=facets.processor%255B%255D%3DCore%2B5&p%5B%5D=facets.processor%255B%255D%3DCore%2B5%2B%2528Series%2B2%2529&p%5B%5D=facets.processor%255B%255D%3DCore%2B7&p%5B%5D=facets.processor%255B%255D%3DCore%2B7%2B%2528Series%2B2%2529&p%5B%5D=facets.processor%255B%255D%3DCore%2BN&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['lap_core_processor_37k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.processor%255B%255D%3DCore%2B3&p%5B%5D=facets.processor%255B%255D%3DCore%2B5&p%5B%5D=facets.processor%255B%255D%3DCore%2B5%2B%2528Series%2B2%2529&p%5B%5D=facets.processor%255B%255D%3DCore%2B7&p%5B%5D=facets.processor%255B%255D%3DCore%2B7%2B%2528Series%2B2%2529&p%5B%5D=facets.processor%255B%255D%3DCore%2BN&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D37000'])
    arr.append(['lap_coreultra_processor_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.processor%255B%255D%3DCore%2BUltra%2B5&p%5B%5D=facets.processor%255B%255D%3DCore%2BUltra%2B7&p%5B%5D=facets.processor%255B%255D%3DCore%2BUltra%2B9&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['lap_coreultra_processor_58k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.processor%255B%255D%3DCore%2BUltra%2B5&p%5B%5D=facets.processor%255B%255D%3DCore%2BUltra%2B7&p%5B%5D=facets.processor%255B%255D%3DCore%2BUltra%2B9&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D58000'])
    arr.append(['lap_iprocessor_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi5&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi3&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi7&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi9&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['lap_i79processor_50k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi7&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi9&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D50000'])
    arr.append(['lap_i35processor_30k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi3&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi5'])
    arr.append(['lap_type_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_desc&p%5B%5D=facets.type%255B%255D%3DCreator%2BLaptop&p%5B%5D=facets.type%255B%255D%3DGaming%2BLaptop&p%5B%5D=facets.type%255B%255D%3D2%2Bin%2B1%2BGaming%2BLaptop&p%5B%5D=facets.type%255B%255D%3D2%2Bin%2B1%2BLaptop&p%5B%5D=facets.type%255B%255D%3DNotebook&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['lap_type_50k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.type%255B%255D%3DCreator%2BLaptop&p%5B%5D=facets.type%255B%255D%3DGaming%2BLaptop&p%5B%5D=facets.type%255B%255D%3D2%2Bin%2B1%2BGaming%2BLaptop&p%5B%5D=facets.type%255B%255D%3D2%2Bin%2B1%2BLaptop&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D50000'])
    arr.append(['lap_touch_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.touch_screen%255B%255D%3DYes&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['lap_amdprocessor_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BAI%2B5%2BHexa%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BAI%2B5%2BQuad%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BAI%2B7%2BOcta%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BAI%2B9%2B10%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BAI%2B9%2B12%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BAI%2BMax&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B9%2B16%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B7%2BHexa%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BZ1%2BHexaCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BZ1%2BOcta%2BCore&p%5B%5D=facets.processor%255B%255D%3DAMD%2BRyzen%2BZ1%2BExtreme&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B5%2BHexa%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B7%2BOcta%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B5%2BQuad%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B7%2BQuad%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B9%2BOcta%2BCore&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['lap_new_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_desc&p%5B%5D=facets.new_arrival%255B%255D%3DNew%2BArrivals&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['lap_brand_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.brand%255B%255D%3DALIENWARE&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.brand%255B%255D%3DAcer&p%5B%5D=facets.brand%255B%255D%3DMICROSOFT'])
    arr.append(['lap_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.fulfilled_by%255B%255D%3DF-Assured'])
    arr.append(['lap_graphprocbrand_50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&marketplace=FLIPKART&otracker=product_breadCrumbs_Laptops&sort=price_asc&p%5B%5D=facets.graphic_processor_name%255B%255D%3DNVIDIA%2BGeForce%2BGTX&p%5B%5D=facets.graphic_processor_name%255B%255D%3DNVIDIA%2BGeForce%2BRTX&p%5B%5D=facets.graphic_processor_name%255B%255D%3DNVIDIA%2BGeforce&p%5B%5D=facets.graphic_processor_name%255B%255D%3DNVIDIA%2BQuadro&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['lap_aiopc_50p.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/all-in-one-pcs/pr?sid=6bo,nl4,igk&p[]=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p[]=facets.fulfilled_by%255B%255D%3DF-Assured&p[]=facets.brand%255B%255D%3DDELL&p[]=facets.brand%255B%255D%3DLenovo&p[]=facets.brand%255B%255D%3DApple&p[]=facets.brand%255B%255D%3DHP&p[]=facets.brand%255B%255D%3DAcer&p[]=facets.brand%255B%255D%3DASUS&p[]=facets.brand%255B%255D%3DHP%2BProdesk&p[]=facets.brand%255B%255D%3DMSI&otracker=categorytree'])
    arr.append(['lap_laptopU10k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['lap_laptop50pU20k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['lap_hpu40K.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['lap_hpU20k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['lap_hp40p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore'])
    arr.append(['lap_hp40U60k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D60000'])
    arr.append(['lap_hp40pU40k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['lap_hp40pu20k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['lap_hpnewest40p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore'])
    arr.append(['lap_hpnew40pU50k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D50000'])
    arr.append(['lap_dellu40k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DDELL&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['lap_dellu20k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DDELL&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['lap_dell40p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DDELL&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore'])
    arr.append(['lap_dell30pU40k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['lap_dell30p60k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D60000'])
    arr.append(['lap_dellnewes40p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DDELL&sort=recency_desc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore'])
    arr.append(['lap_dellnewest30p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DDELL&sort=recency_desc&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore'])
    arr.append(['lap_dellnewest30pU50k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D50000'])
    arr.append(['lap_dell30pU75k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D75000'])
    arr.append(['lap_appleU50k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D50000'])
    arr.append(['lap_appleU20kk.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['lap_appleU60k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D60000'])
    arr.append(['lap_applenewu60k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D60000'])
    arr.append(['lap_applenewU75k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D75000'])
    arr.append(['lap_applenewestU20k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['lap_msiU40k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['lap_msiU2ok.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['lap_msi20p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.discount_range_v1%255B%255D%3D20%2525%2Bor%2Bmore'])
    arr.append(['lap_msi20pU50k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D20%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D50000'])
    arr.append(['lap_msinewU40k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['lap_msinewestU2ok.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['lap_alienwareU75k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DALIENWARE&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D75000'])
    arr.append(['lap_alienwareU1.5.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DALIENWARE&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D150000'])
    arr.append(['lap_lenovoU20k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['lap_lenovoU40k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['lap_lenovo50p.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['lap_lenovo50pU40k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['lap_lenovonewestU30k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DLenovo&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['lap_lenovonewU20k.txt', 1, False, 'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DLenovo&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])

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
        process = Thread(target=flipkart_parse, args=[
            filename, telegram, force, url, res_queue, notassured])
        process.setDaemon(True)
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
    #pcmemory()

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
        clear()
        dowork()
    except Exception as e:
        logger.error(str(e))
        logger.error(traceback.format_exc())

    d = datetime.datetime.now(pytz.timezone("Asia/Kolkata"))
    hour = d.hour
    if hour >= 3 and hour <= 7:
        sleep = random.randint(5, 10)
        print("----------------------- Sleeping for " +
              str(sleep) + " Seconds ------------------------")
        time.sleep(sleep)
    else:
        sleep = random.randint(2, 3)
        print("----------------------- Sleeping for " +
              str(sleep) + " Seconds ------------------------")
        time.sleep(sleep)


