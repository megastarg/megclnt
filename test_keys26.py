import subprocess
from curl_cffi import requests
import re
import json

# Start a headless browser to get cookies using a python script without playwright module directly
# Wait, playwright was not installed, so I can't use it.
