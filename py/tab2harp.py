'''
a simple script for converting guitar tabs into a single line of notes, that can be used to play harmonica.
error checking is omited for a laziness's sake
'''
import sys
STRINGS = ( #notes up to 15th fret
			#1 string
			('E','F','F#','G','G#','A','A#','B','C','C#','D','D#','E','F','F#','G'),
			#2
			('B','C','C#','D','D#','E','F','F#','G','G#','A','A#','B','C','C#','D'),
			#3
			('G','G#','A','A#','B','C','C#','D','D#','E','F','F#','G','G#','A','A#'),
			#4
			('D','D#','E','F','F#','G','G#','A','A#','B','C','C#','D','D#','E','F'),
			#5
			('A','A#','B','C','C#','D','D#','E','F','F#','G','G#','A','A#','B','C'),
			#6
			('E','F','F#','G','G#','A','A#','B','C','C#','D','D#','E','F','F#','G')
		)
try:
	with open('input.txt') as f:
		raw = f.readlines()
except:
	sys.exit("ooops...can't open the file")
#filter and packing lines into blocks:
lines = list()
for i,line in enumerate(filter(lambda l: '--' in l,raw)):
	if not i % 6: #pack every six lines in a single list
		lines.append(list())
	lines[i//6].append(line.strip())

#converting blocks into dictionaries
#and checking notes:
out_tab = list()
for block in lines:
	buff = dict() #create a dict for each tab block
	for str_num,line in enumerate(block):
		buff['length'] = len(line)
		for i,sym in enumerate(line):
			if sym.isnumeric():
				if line[i+1].isnumeric(): #here we can omit checking for the length, as tabs almost always have closing bars(i.e. non-numerics signs)
					if i in buff:
						buff[i] = buff[i]+'+'+STRINGS[str_num][int(''.join([sym,line[i+1]]))]
					else:
						buff[i] =STRINGS[str_num][int(''.join([sym,line[i+1]]))]
				elif i in buff: #check for vertical repeat
					buff[i] = buff[i]+'+'+STRINGS[str_num][int(sym)] # if there are more than one note in column, then merge them
				elif not line[i-1].isnumeric():
					buff[i] = STRINGS[str_num][int(sym)]
			elif '|' in sym or '~' in sym or '\\' in sym:
				buff[i] = sym
	out_tab.append(buff)

#printing out notes:
for n,tab_dict in enumerate(out_tab):
	print(n,':')
	for offset in range(0,tab_dict['length']):
		if offset in tab_dict:
			out_note = tab_dict[offset]
		else:
			out_note = '-'
		print(out_note,end='')
	print('\n')

	


