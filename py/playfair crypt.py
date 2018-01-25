#!/usr/bin/python3
import codecs
import sys
from random import shuffle
#run flags:
DECR = True
#
#char typles:
ABC_ru_36 = ('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я',' ','.',',')
abc_ru_36 = ('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ','.',',')
ABC_en_29 = ('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',' ','.',',')
abc_en_29 = ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',' ','.','.')
#
def generateKeys():
	try:
		with codecs.open('key1.txt',encoding='utf-8', mode='w') as k1File:
			key1 = list(ABC_ru_36)
			shuffle(key1)
			for char in key1:
				k1File.write(char)
	except IOError:
		print("Could not write file:", k1File)
	k1File.close()

	try:
		with codecs.open('key2.txt',encoding='utf-8', mode='w') as k2File:
			key2 = list(ABC_ru_36)
			shuffle(key2)
			for char in key2:
				k2File.write(char)
	except IOError:
		print("Could not write file:", k2File)
	k2File.close()
	print("new key files were generated: 'key1.txt' and 'key2.txt'")
	return True
#main program:
args = str(sys.argv)
if '-enc' in args:
	DECR = False
if '-keys' in args:
	generateKeys()
	DECR = False


#open key1.txt
try:
	with codecs.open('key1.txt',encoding='utf-8', mode='r') as fKey1:
		key1 = fKey1.read()
except IOError:
	print("Could not open file 'key1.txt'")
	quit()
fKey1.close()
#open key2.txt
try:
	with codecs.open('key2.txt',encoding='utf-8', mode='r') as fKey2:
		key2 = fKey2.read()
except IOError:
	print("Could not open file 'key2.txt'")
	quit()
fKey2.close()
#open plain.txt
if not DECR:
	try:
		with codecs.open('plain.txt',encoding='utf-8', mode='r') as fPlain:
			inputText = fPlain.read().upper()
	except IOError:
		print("Could not open file 'plain.txt'")
		quit()
	fPlain.close()
#open encrypted.txt
else:
	try:
		with codecs.open('encrypted.txt',encoding='utf-8', mode='r') as fEnc:
			inputText = fEnc.read()
	except IOError:
		print("Could not open file 'encrypted.txt'")
		quit()
	fEnc.close()

#encryption proccess:
#1.if the length is not even, we're writing the last symbol as is plus space after it
#2.we're transform all input text from plain.txt to upper case and write output as text in upper case
#3.we're skip all the symbols that are not find in key matrices
#
outputText = list()
inputLen = len(inputText)
for i in range(0,inputLen,2):
	if i+1 < inputLen:#if lenght is not even we're not encrypting symbols
		indK1 = key1.find(inputText[i])
		if inputText[i]==inputText[i+1] and not DECR:
			indK2 = key2.find('Ъ')
		else:
			indK2 = key2.find(inputText[i+1])

		if indK1!=-1 and indK2!=-1:
			row1 = indK1//6
			col1 = indK1%6
			row2 = indK2//6
			col2 = indK2%6

			if col1==col2:
				row1 = (row1+1)%6 if not DECR else (row1-1)%6
				row2 = (row2+1)%6 if not DECR else (row2-1)%6

			outputText.append(key1[row1*6+col2])
			outputText.append(key2[row2*6+col1])
		else:
			outputText.append(inputText[i])
			outputText.append(inputText[i+1])
	else:
		outputText.append(inputText[i])
		outputText.append(' ')
#we're done with the encryption

#write output file:
if not DECR:
	try:
		with codecs.open('encrypted.txt',encoding='utf-8', mode='w') as fOutput:
			for char in outputText:
				fOutput.write(char)
			print("text from 'plain.txt' was encrypted into file 'encrypted.txt'")
	except IOError:
		print("Could not write file 'encrypted.txt'")
		quit()
	fOutput.close()
else:
	try:
		with codecs.open('decrypted.txt',encoding='utf-8', mode='w') as fOutput:
			for char in outputText:
				fOutput.write(char)
			print("text from 'encrypted.txt' was decrtypted into file 'decrypted.txt'")
	except IOError:
		print("Could not write file 'decrypted.txt'")
		quit()
	fOutput.close()
print("everything went ok")