import json

with open("api_response.json", "r") as f:
    data = json.load(f)
    print("Keys in root:", data.keys())
    if "RESPONSE" in data:
        print("Keys in RESPONSE:", data["RESPONSE"].keys())
        if "pageData" in data["RESPONSE"]:
            print("Keys in pageData:", data["RESPONSE"]["pageData"].keys())
        if "pageDataV4" in data["RESPONSE"]:
            print("Keys in pageDataV4:", data["RESPONSE"]["pageDataV4"].keys())
