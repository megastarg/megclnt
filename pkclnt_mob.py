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
from mainmob import flipkart_parse
from block import block

logger = logging.getLogger("Rotating Log")
logger.setLevel(logging.ERROR)
handler = RotatingFileHandler("log.txt", maxBytes=10000, backupCount=5)
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
handler.setFormatter(formatter)
logger.addHandler(handler)

foldername = "M_megaclntw_hkaayu_spl/"
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
    arr.append(['mob_tabU10k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&otracker=categorytree&sort=price_asc'])
    arr.append(['mob_tab40pU15k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&otracker=categorytree&sort=price_asc&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['mob_appletabU2ok.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_appletabU30k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_appleU30kex.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_appletab30p.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore'])
    arr.append(['mob_appletab30pU40k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['mob_appletab20p40k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D20%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D40000'])
    arr.append(['mob_appletabnewestU30k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['mob_applenewestO2ok.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_applenewest30p.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.discount_range_v1%255B%255D%3D30%2525%2Bor%2Bmore'])
    arr.append(['mob_samsungtabU10k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['mob_samsungtabU20k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_samsung40pex.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore'])
    arr.append(['mob_samsung4opexU20k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&otracker=categorytree&sort=price_asc&p%5B%5D=facets.availability%255B%255D%3DExclude%2BOut%2Bof%2BStock&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_samsung40pU20k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DSamsung&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['mob_samsungnewesU10k.txt', 1, False, 'https://www.flipkart.com/tablets/pr?sid=tyy%2Chry&otracker=categorytree&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000&p%5B%5D=facets.brand%255B%255D%3DSamsung'])
    arr.append(['comp_asusgfcU20k.txt', 1, False, 'https://www.flipkart.com/computers/computer-components/graphic-cards/pr?sid=6bo%2Cg0i%2C6sn&q=gpu+for+pc&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['comp_nvidia30k.txt', 1, False, 'https://www.flipkart.com/computers/computer-components/graphic-cards/pr?sid=6bo%2Cg0i%2C6sn&q=gpu+for+pc&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3Dgpu&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['comp_compU10k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.brand%255B%255D%3DAcer&p%5B%5D=facets.brand%255B%255D%3DIntel&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['comp_comp50U20k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.brand%255B%255D%3DAcer&p%5B%5D=facets.brand%255B%255D%3DIntel&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['comp_compdellU1ok.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['comp_compdellU15K.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D15000'])
    arr.append(['comp_compappleU60k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D60000'])
    arr.append(['comp_compappleU30k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['comp_comphpU20k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['comp_comphpU10k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['comp_comphp50p.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DHP'])
    arr.append(['comp_asusU30k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DASUS&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['comp_asusU50k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DASUS&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D50000'])
    arr.append(['comp_asusU40p.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DASUS&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore'])
    arr.append(['comp_asus40p3ok.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['comp_asusnewestU30k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DASUS&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['comp_asusneweU50k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&p%5B%5D=facets.brand%255B%255D%3DASUS&sort=recency_desc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D50000'])
    arr.append(['comp_lgU10k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DLG'])
    arr.append(['comp_hpeliteU10k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DHP%2BEliteDesk&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['comp_intelU1ok.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DIntel&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['comp_acerU30k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DAcer'])
    arr.append(['comp_zebronicU7k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DZEBRONICS'])
    arr.append(['comp_lenovU30k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['comp_lenovo50p.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['comp_lenovo40pu30k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D40%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D30000'])
    arr.append(['comp_otherU7k.txt', 1, False, 'https://www.flipkart.com/computers-accessories/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DDELL&p%5B%5D=facets.brand%255B%255D%3DLenovo&p%5B%5D=facets.brand%255B%255D%3DApple&p%5B%5D=facets.brand%255B%255D%3DZEBRONICS&p%5B%5D=facets.brand%255B%255D%3DHP&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.brand%255B%255D%3DAcer&p%5B%5D=facets.brand%255B%255D%3DIntel&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D10000'])
    arr.append(['comp_frontechU5K.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DFrontech&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D5000'])
    arr.append(['comp_iballu5k.txt', 1, False, 'https://www.flipkart.com/computers/desktop-pcs/pr?sid=6bo%2Cnl4&otracker=categorytree&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DiBall&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D5000'])
    arr.append(['comp_amdgraphicscardU3ok.txt', 1, False, 'https://www.flipkart.com/gaming-components/graphic-cards/pr?sid=4rr%2Ctin%2C6zn&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DAMD&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D5000'])
    arr.append(['comp_msigfU50k.txt', 1, False, 'https://www.flipkart.com/gaming-components/graphic-cards/msi~brand/pr?sid=4rr%2Ctin%2C6zn&otracker=categorytree&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D50000'])
    arr.append(['comp_gigabyteU2ok.txt', 1, False, 'https://www.flipkart.com/gaming-components/gigabyte~brand/pr?sid=4rr%2Ctin%2C6zn&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['comp_asusU20k.txt', 1, False, 'https://www.flipkart.com/gaming-components/asus~brand/pr?sid=4rr%2Ctin%2C6zn&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D20000'])
    arr.append(['comp_zotacU25k.txt', 1, False, 'https://www.flipkart.com/gaming-components/zotac~brand/pr?sid=4rr%2Ctin%2C6zn&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D25000'])
    arr.append(['comp_graphiccardU4500.txt', 1, False, 'https://www.flipkart.com/gaming-components/graphic-cards/pr?sid=4rr%2Ctin%2C6zn&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.brand%255B%255D%3DAMD&p%5B%5D=facets.brand%255B%255D%3DFrontech&p%5B%5D=facets.brand%255B%255D%3DGIGABYTE&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.brand%255B%255D%3DnVIDIA&p%5B%5D=facets.brand%255B%255D%3DZEBRONICS&p%5B%5D=facets.brand%255B%255D%3DZOTAC&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D4500'])
    arr.append(['comp_graphics50p.txt', 1, False, 'https://www.flipkart.com/gaming-components/graphic-cards/pr?sid=4rr%2Ctin%2C6zn&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.brand%255B%255D%3DAMD&p%5B%5D=facets.brand%255B%255D%3DFrontech&p%5B%5D=facets.brand%255B%255D%3DGIGABYTE&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.brand%255B%255D%3DnVIDIA&p%5B%5D=facets.brand%255B%255D%3DZEBRONICS&p%5B%5D=facets.brand%255B%255D%3DZOTAC&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore'])
    arr.append(['comp_graphics50pu7k.txt', 1, False, 'https://www.flipkart.com/gaming-components/graphic-cards/pr?sid=4rr%2Ctin%2C6zn&marketplace=FLIPKART&sort=price_asc&p%5B%5D=facets.discount_range_v1%255B%255D%3D50%2525%2Bor%2Bmore&p%5B%5D=facets.brand%255B%255D%3DASUS&p%5B%5D=facets.brand%255B%255D%3DAMD&p%5B%5D=facets.brand%255B%255D%3DFrontech&p%5B%5D=facets.brand%255B%255D%3DGIGABYTE&p%5B%5D=facets.brand%255B%255D%3DMSI&p%5B%5D=facets.brand%255B%255D%3DnVIDIA&p%5B%5D=facets.brand%255B%255D%3DZEBRONICS&p%5B%5D=facets.brand%255B%255D%3DZOTAC&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D7500'])
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