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

foldername = "spl1w_jas_amz_2/"
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

    arr.append(['tv_18k.txt', 1, False, 'https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&p[]=facets.screen_size%255B%255D%3D48%2B-%2B55%2Binch&otracker=categorytree&sort=price_asc&p[]=facets.screen_size%255B%255D%3D60%2Binch%2B%2BAbove&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D18000'])
    arr.append(['treadmill_15k.txt', 1,False,'https://www.flipkart.com/exercise-fitness/fitness-equipment/treadmills/pr?sid=qoc%2Camf%2Coyq&marketplace=FLIPKART&otracker=product_breadCrumbs_Treadmills&p[]=facets.fulfilled_by%255B%255D%3DFlipkart%2BAssured&sort=price_asc&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D6000'])
    arr.append(['tv_4k8k_21k.txt', 1, False,'https://www.flipkart.com/search?sid=czl&otracker=CLP_Filters&sort=price_asc&p[]=facets.fulfilled_by%255B%255D%3DPlus%2B%2528FAssured%2529&p[]=facets.resolution%255B%255D%3DUltra%2BHD%2B%25284K%2529&p[]=facets.resolution%255B%255D%3DUltra%2BHD%2B%25288K%2529&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D21000'])
    arr.append(['tv_10k50p.txt', 1,False,'https://www.flipkart.com/search?sid=czl&otracker=CLP_Filters&sort=discount&p[]=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D7000&p[]=facets.brand%255B%255D%3Drealme&p[]=facets.brand%255B%255D%3DMi&p[]=facets.brand%255B%255D%3DOnePlus&p[]=facets.brand%255B%255D%3DSAMSUNG&p[]=facets.brand%255B%255D%3DLG&p[]=facets.brand%255B%255D%3DSONY&p[]=facets.brand%255B%255D%3DThomson&p[]=facets.brand%255B%255D%3DMOTOROLA&p[]=facets.brand%255B%255D%3DInfinix&p[]=facets.brand%255B%255D%3DPanasonic&p[]=facets.brand%255B%255D%3DTCL&p[]=facets.brand%255B%255D%3DiFFALCON&p[]=facets.brand%255B%255D%3DHisense&p[]=facets.brand%255B%255D%3DNokia&p[]=facets.brand%255B%255D%3DHaier&p[]=facets.brand%255B%255D%3DMicromax&p[]=facets.brand%255B%255D%3DLloyd&p[]=facets.brand%255B%255D%3DPHILIPS&p[]=facets.brand%255B%255D%3DTOSHIBA&p[]=facets.brand%255B%255D%3Dacer&p[]=facets.brand%255B%255D%3DVu&p[]=facets.brand%255B%255D%3DBlaupunkt&p[]=facets.brand%255B%255D%3DHyundai&p[]=facets.brand%255B%255D%3DONIDA&p[]=facets.brand%255B%255D%3DIntex&p[]=facets.brand%255B%255D%3DKODAK&p[]=facets.brand%255B%255D%3DSansui&p[]=facets.brand%255B%255D%3DCompaq'])
    arr.append(['fridge_10k.txt', 0,False,'https://www.flipkart.com/search?sid=j9e%2Fabm%2Fhzg&otracker=CLP_Filters&p[]=facets.fulfilled_by%255B%255D%3DFlipkart%2BAssured&sort=discount&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D7000'])
    arr.append(['fans_1000.txt', 0,False,'https://www.flipkart.com/fans/pr?sid=j9e%2Cabm%2Clbz&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DAtomberg&p%5B%5D=facets.brand%255B%255D%3DCrompton&p%5B%5D=facets.brand%255B%255D%3DOrient%2BElectric&p%5B%5D=facets.brand%255B%255D%3DUSHA&p%5B%5D=facets.brand%255B%255D%3DHAVELLS&p%5B%5D=facets.brand%255B%255D%3DBAJAJ&p%5B%5D=facets.brand%255B%255D%3DPolycab&p%5B%5D=facets.brand%255B%255D%3DLUMINOUS&p%5B%5D=facets.brand%255B%255D%3DV-Guard&p%5B%5D=facets.brand%255B%255D%3DVenus&p%5B%5D=facets.brand%255B%255D%3DHindware&p%5B%5D=facets.brand%255B%255D%3DHavells%2BElectrical&p%5B%5D=facets.brand%255B%255D%3DSyska&p%5B%5D=facets.brand%255B%255D%3DPanasonic&p%5B%5D=facets.brand%255B%255D%3DHALONIX&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D1000'])
    arr.append(['cycles_3000.txt', 1,False,'https://www.flipkart.com/sports/cycling/cycles/adult-cycles/pr?sid=abc%2Culv%2Cixt%2Ci5v&p[]=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&otracker=categorytree&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D3000'])
    arr.append(['appliances_500.txt', 0,False,'https://www.flipkart.com/home-kitchen/~appliances-for-a-healthy-living/pr?sid=j9e&otracker=nmenu_sub_TVs+%26+Appliances_0_Healthy+Living+Appliances&sort=price_asc&p[]=facets.fulfilled_by%255B%255D%3DFlipkart%2BAssured&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D600'])
    arr.append(['AC_20k.txt', 1,False,'https://www.flipkart.com/air-conditioners/pr?sid=j9e%2Cabm%2Cc54&p[]=facets.fulfilled_by%255B%255D%3DFlipkart%2BAssured&otracker=categorytree&otracker=nmenu_sub_TVs+%26+Appliances_0_Air+Conditioners&sort=discount&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D21000'])
    arr.append(['laptop_15k.txt', 1,False,'https://www.flipkart.com/search?sid=6bo%2Cb5g&otracker=CLP_Filters&p[]=facets.fulfilled_by%255B%255D%3DFlipkart%2BAssured&sort=price_asc&p[]=facets.serviceability[]%3Dtrue&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D10000'])
    arr.append(['laptop_50p40k.txt', 1,False,'https://www.flipkart.com/laptops/pr?p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&count=40&affid=sriyaz083&sid=6bo%2Cb5g&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000&p%5B%5D=facets.fulfilled_by%255B%255D%3DPlus%2B%2528FAssured%2529'])
    arr.append(['laptop_50p25k.txt', 1,False,'https://www.flipkart.com/search?sid=6bo%2Cb5g&otracker=CLP_Filters&p%5B%5D=facets.price_range.from%3DMin&sort=price_asc&p%5B%5D=facets.price_range.to%3D25000&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['laptop_brand50p25k.txt', 1,False,'https://www.flipkart.com/laptops/pr?sid=6bo%2Cb5g&otracker=categorytree&sort=price_asc&p[]=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p[]=facets.brand%255B%255D%3DHP&p[]=facets.brand%255B%255D%3DASUS&p[]=facets.brand%255B%255D%3DLenovo&p[]=facets.brand%255B%255D%3DDELL&p[]=facets.brand%255B%255D%3DMSI&p[]=facets.brand%255B%255D%3DAPPLE&p[]=facets.brand%255B%255D%3DMi&p[]=facets.brand%255B%255D%3DAvita&p[]=facets.brand%255B%255D%3DREDMI&p[]=facets.brand%255B%255D%3DAcer&p[]=facets.brand%255B%255D%3DInfinix&p[]=facets.brand%255B%255D%3DMICROSOFT&p[]=facets.brand%255B%255D%3DSAMSUNG&p[]=facets.brand%255B%255D%3DALIENWARE&p[]=facets.brand%255B%255D%3Drealme&p[]=facets.brand%255B%255D%3DVaio&p[]=facets.brand%255B%255D%3DGIGABYTE&p[]=facets.brand%255B%255D%3DLG&p[]=facets.brand%255B%255D%3DCHUWI&p[]=facets.brand%255B%255D%3DNokia&p[]=facets.brand%255B%255D%3DMicromax&p[]=facets.brand%255B%255D%3DLG%2BGram&p[]=facets.brand%255B%255D%3DHonor&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D25000'])
    arr.append(['laptop_processor50p30k.txt', 1,False,'https://www.flipkart.com/laptops/pr?count=40&affid=sriyaz083&sid=6bo%2Cb5g&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi5&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi7&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B7%2BQuad%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B5%2BQuad%2BCore&p%5B%5D=facets.processor%255B%255D%3DCore%2Bi9&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B5%2BHexa%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B7%2BOcta%2BCore&p%5B%5D=facets.processor%255B%255D%3DM1&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B5&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B5%2BDual%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B5%2BOcta%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B7%2BDual%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B7%2BHexa%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B9%2B12%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B9%2B16%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2B9%2BOcta%2BCore&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BZ1%2BOcta%2BCore&p%5B%5D=facets.processor%255B%255D%3DHexa%2BCore%2Bi5&p%5B%5D=facets.processor%255B%255D%3DRyzen%2BZ1%2BHexaCore'])
    arr.append(['laptop_type50p50k.txt', 1,False,'https://www.flipkart.com/laptops/pr?count=40&affid=sriyaz083&sid=6bo%2Cb5g&sort=price_asc&p[]=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p[]=facets.type%255B%255D%3DGaming%2BLaptop&p[]=facets.type%255B%255D%3D2%2Bin%2B1%2BLaptop&p[]=facets.type%255B%255D%3D2%2Bin%2B1%2BGaming%2BLaptop&p[]=facets.type%255B%255D%3DBusiness%2BLaptop&p[]=facets.type%255B%255D%3DHandheld%2BGaming%2BPC&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D50000'])
    arr.append(['mob_3k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc&p[]=facets.fulfilled_by%255B%255D%3DFlipkart%2BAssured&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D3000&p[]=facets.type%255B%255D%3DSmartphones&p[]=facets.serviceability[]%3Dfalse'])
    arr.append(['mob1.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D5000&p[]=facets.type%255B%255D%3DSmartphones&p[]=facets.brand%255B%255D%3DMi&p[]=facets.brand%255B%255D%3DHonor&p[]=facets.brand%255B%255D%3DOPPO&p[]=facets.brand%255B%255D%3DGoogle&p[]=facets.brand%255B%255D%3DHTC&p[]=facets.brand%255B%255D%3DHuawei&p[]=facets.brand%255B%255D%3DLenovo&p[]=facets.brand%255B%255D%3DLG&p[]=facets.brand%255B%255D%3DMeizu&p[]=facets.brand%255B%255D%3DNokia&p[]=facets.brand%255B%255D%3Drealme&p[]=facets.brand%255B%255D%3DSAMSUNG&p[]=facets.brand%255B%255D%3DASUS&p[]=facets.brand%255B%255D%3DAPPLE&p[]=facets.brand%255B%255D%3DBlackBerry&p[]=facets.brand%255B%255D%3DInfocus&p[]=facets.brand%255B%255D%3DMOTOROLA&p[]=facets.brand%255B%255D%3DSONY&p[]=facets.brand%255B%255D%3Dvivo&p[]=facets.brand%255B%255D%3DPOCO&p[]=facets.brand%255B%255D%3DInfinix&p[]=facets.brand%255B%255D%3DOnePlus&p[]=facets.brand%255B%255D%3DREDMI&p[]=facets.brand%255B%255D%3DTecno'])
    arr.append(['mob2.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&otracker=product_breadCrumbs_Mobiles&p[]=facets.price_range.from%3DMin&sort=price_asc&p[]=facets.price_range.to%3D3500&p[]=facets.type%255B%255D%3DSmartphones&p[]=facets.ram%255B%255D%3D4%2BGB&p[]=facets.ram%255B%255D%3D3%2BGB&p[]=facets.ram%255B%255D%3D2%2BGB&p[]=facets.ram%255B%255D%3D6%2BGB&p[]=facets.ram%255B%255D%3D8%2BGB%2Band%2BAbove'])
    arr.append(['mob3.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&otracker=product_breadCrumbs_Mobiles&sort=price_asc&p%5B%5D=facets.internal_storage%255B%255D%3D256%2BGB%2B%2526%2BAbove&p%5B%5D=facets.internal_storage%255B%255D%3D128%2B-%2B255.9%2BGB&p%5B%5D=facets.internal_storage%255B%255D%3D64%2B-%2B127.9%2BGB&p%5B%5D=facets.internal_storage%255B%255D%3D32%2B-%2B63.9%2BGB&p%5B%5D=facets.internal_storage%255B%255D%3D16%2B-%2B31.9%2BGB&p%5B%5D=facets.internal_storage%255B%255D%3D8%2B-%2B15.9%2BGB&p%5B%5D=facets.internal_storage%255B%255D%3D4%2B-%2B7.9%2BGB&p%5B%5D=facets.internal_storage%255B%255D%3D2%2BGB%2B-%2B3.9%2BGB&p%5B%5D=facets.internal_storage%255B%255D%3D1%2BGB%2B-%2B1.9%2BGB&p%5B%5D=facets.internal_storage%255B%255D%3DLess%2Bthan%2B1%2BGB&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&p%5B%5D=facets.type%255B%255D%3DSmartphones&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D3500'])
    arr.append(['mob4.txt', 1,False,'https://www.flipkart.com/mobiles/~smartphones-under-rs15000/pr?sid=tyy%2C4io&sort=price_asc&p[]=facets.fulfilled_by%255B%255D%3DFlipkart%2BAssured&p[]=facets.serviceability[]%3Dfalse&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D3500'])
    arr.append(['mob50p5g.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc&p[]=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p[]=facets.network_type%255B%255D%3D5G&p[]=facets.type%255B%255D%3DSmartphones&p[]=facets.brand%255B%255D%3DMi&p[]=facets.brand%255B%255D%3DHonor&p[]=facets.brand%255B%255D%3DOPPO&p[]=facets.brand%255B%255D%3DGoogle&p[]=facets.brand%255B%255D%3DHuawei&p[]=facets.brand%255B%255D%3DLenovo&p[]=facets.brand%255B%255D%3DLG&p[]=facets.brand%255B%255D%3DMeizu&p[]=facets.brand%255B%255D%3DNokia&p[]=facets.brand%255B%255D%3Drealme&p[]=facets.brand%255B%255D%3DSAMSUNG&p[]=facets.brand%255B%255D%3DASUS&p[]=facets.brand%255B%255D%3DAPPLE&p[]=facets.brand%255B%255D%3DBlackBerry&p[]=facets.brand%255B%255D%3DInfocus&p[]=facets.brand%255B%255D%3DMOTOROLA&p[]=facets.brand%255B%255D%3DSONY&p[]=facets.brand%255B%255D%3Dvivo&p[]=facets.brand%255B%255D%3DPOCO&p[]=facets.brand%255B%255D%3DInfinix&p[]=facets.brand%255B%255D%3DOnePlus&p[]=facets.brand%255B%255D%3DREDMI&p[]=facets.brand%255B%255D%3DTecno&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D15000'])
    arr.append(['mob50p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&otracker=product_breadCrumbs_Mobiles&sort=price_asc&p[]=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p[]=facets.type%255B%255D%3DSmartphones&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D5000'])
    arr.append(['mob_big.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D25000&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&p%5B%5D=facets.brand%255B%255D%3DGoogle&p%5B%5D=facets.brand%255B%255D%3DOnePlus&p%5B%5D=facets.brand%255B%255D%3DAPPLE&p%5B%5D=facets.brand%255B%255D%3DSONY&p%5B%5D=facets.brand%255B%255D%3Drealme&p%5B%5D=facets.brand%255B%255D%3DInfinix&p%5B%5D=facets.brand%255B%255D%3DSAMSUNG&p%5B%5D=facets.brand%255B%255D%3DMi&p%5B%5D=facets.brand%255B%255D%3DInfocus&p%5B%5D=facets.brand%255B%255D%3DOPPO&p%5B%5D=facets.brand%255B%255D%3Dvivo&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.brand%255B%255D%3DASUS'])
    arr.append(['mob_apple_u30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_apple_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DApple&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_apple_u15k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DApple&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['mob_google_u40k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DGoogle&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['mob_google_u30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DGoogle&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_google_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DGoogle&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_google_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DGoogle&otracker=categorytree&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_google_20p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DGoogle&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D20%2525%2Bor%2Bmore'])
    arr.append(['mob_google_20p_30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D20%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DGoogle&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_nothing_u30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DNothing&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_nothing_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DNothing&otracker=categorytree&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_nothing_newest_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DNothing&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_nothing_30p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DNothing&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=price_asc'])
    arr.append(['mob_nothing_30p_30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000&p%5B%5D=facets.brand%255B%255D%3DNothing'])
    arr.append(['mob_nothing_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DNothing&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_oneplus_u30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DOnePlus&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_oneplus_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DOnePlus&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_oneplus_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DOnePlus&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_iqoo_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DIQOO&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_iqoo_50p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DIQOO&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_iqoo_40p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3DIQOO&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore'])
    arr.append(['mob_iqoo_40p_15k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DIQOO&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['mob_iqoo_40p_30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DIQOO&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_iqoo_50p_30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000&p%5B%5D=facets.brand%255B%255D%3DIQOO'])
    arr.append(['mob_motorola_50p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DMOTOROLA&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_motorola_50p_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DMOTOROLA&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_motorola_40p_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DMOTOROLA&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_vivo_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3Dvivo&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_vivo_50p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3Dvivo&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&sort=price_asc'])
    arr.append(['mob_vivo_50p15k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3Dvivo&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['mob_vivo_50p20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3Dvivo&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_oppo_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DOPPO&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_oppo_50p20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DOPPO&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_oppo_50p_15k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DOPPO&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['mob_oppo_40p_10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DOPPO&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_infinix_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DInfinix&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_infinix_7k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DInfinix&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D7000'])
    arr.append(['mob_infinix_30p_10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DInfinix&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_poco_u7k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DPOCO&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D7000'])
    arr.append(['mob_poco50p_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DPOCO&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_poco50p_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DPOCO&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_redmi_u7k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D7000'])
    arr.append(['mob_redmi_50p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_redmi_50p_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_redmi_50p_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_redmi_40p_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DREDMI&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_lava_40p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DLAVA&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore'])
    arr.append(['mob_lava_30p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DLAVA&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore'])
    arr.append(['mob_lava_40p_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DLAVA&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_tecno_u5k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DTecno&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D5000'])
    arr.append(['mob_tecno_40p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DTecno&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore'])
    arr.append(['mob_tecno_40p_u15k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DTecno&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['mob_realme_u7k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&p%5B%5D=facets.brand%255B%255D%3Drealme&otracker=categorytree&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D7000&sort=price_asc'])
    arr.append(['mob_realme_50p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3Drealme&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_realme_50p_30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3Drealme&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_realme_40p_20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3Drealme&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_realme_40p_10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3Drealme&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_realme_50p_20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3Drealme&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_realme_50p_15k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3Drealme&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['mob_samsung_u7k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D7000'])
    arr.append(['mob_samsung_50p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_samsung_50p_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_samsung_50p_u20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_samsung_50p_u30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_samsung_40p_u10k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_samsung_newest50p.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['mob_samsung_newest50p_20k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_samsung_newest50p_30k.txt', 1,False,'https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['printer_5k.txt', 0,False,'https://www.flipkart.com/computers/printers-inks/printers/pr?sid=6bo%2Cffn%2Ct64&p[]=facets.fulfilled_by%255B%255D%3DFlipkart%2BAssured&otracker=categorytree&sort=price_asc&p[]=facets.price_range.from%3DMin&p[]=facets.price_range.to%3D2000'])

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
            item=res_queue.get()
            if str(item).find("529 Error")!=-1:
                gotosleep=True
            if item is None:
                break
            fall.write(item + "\r\n")
        fall.write("Telegram = " + telegram)
    res_queue.empty()
    gc.collect()
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