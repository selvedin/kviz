from PIL import Image
import pytesseract
from sys import argv

img = argv[1]

try:
    img = Image.open(str(img)).convert('RGB')
    custom_config = r'-l bos --oem 3 --psm 6'
    result = pytesseract.image_to_string(img, config=custom_config)
    print(str(result.encode('utf-8'))[2:-1])
except Exception as e:
    print(e)
