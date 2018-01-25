#include <stdio.h>
#include <stdlib.h>
#define SIZE 2000
//a little helper function for qsort():
//кароч, это просто вспомогательная функция для стандартной функции сортировки qsort()
//сравнивает два значения и возвращает -1 и 1:
int cmpfunc(const void *p, const void *q) {
    long x = *(const long *)p;
    long y = *(const long *)q;

    if (x < y)
        return 1;  // Return -1 if you want ascending, 1 if you want descending order.
    else if (x > y)
        return -1;   // Return 1 if you want ascending, -1 if you want descending order.

    return 0;
}

//a little helper to write bigram into file:
/*
тут тоже все просто: на вход функции подается биграмма и указатель на файл, в который нужно записать её.
дальше из биграммы достается два символа и поочередно записываются в файл.
*/
int writeBigram(FILE* outputFile,int bigram){
   fputc((bigram / 1000) % 1000,outputFile);
   fputc(bigram % 1000,outputFile);
   return 0;
}
/*
логика проста: функция на входе получает некое число num и указатель на массив из неких отсортированных по убыванию чисел.
далее мы просто ищем в этом массиве ближайшее число к нашему num
*/
int findNearestBigram(int num, unsigned long numArray[]){
   int i=0;
   while(numArray[i]>0){
      int cFreq = numArray[i]/1000000;
      int fFreq = numArray[i+1]/1000000;
      if(num >= cFreq)
         return numArray[i]%1000000;
      else if(num < cFreq && num > fFreq){
         if((cFreq-num) <= (num - fFreq))
            return numArray[i]%1000000;
         else
            return numArray[i+1]%1000000;
       }
      i++;
   }
   return 0;
}
/*
магия начинается как раз тут:
1. открываем файл(либо hackme либо teachme - зависит от флага doHack) для чтения, чтобы позднее и них  читать(внезапно!)
2. дальше в цикле читаем по два символа, поэтому размер файла делим пополам при проверке.
Этим же достигается проверка четности, так как деление целочисленное и мы всегда получаем число кратное двум, хотя и теряем один символ иногда.
В данном случае для простоты этим можно пренебречь.
Позже этом же цикле мы сразу кодируем их в биграмму как A*1000 + B.

на входе функция получает флаг doHack, который отвечает за выбор входящего файла и указатель на массив, в который будут записываться биграммы с частотами.
*/
int analyzeInputText(int doHack, unsigned long outputBuffer[]){//doHack = 1 - analyze encrypted text 'hackme.txt', 0 - 'teachme.txt'
	FILE* inputFile;
	if(doHack)
		inputFile = fopen("hackme.txt","rb");
	else
		inputFile = fopen("teachme.txt","rb");

	if(inputFile == NULL){
		if(doHack)
			printf("cannot open 'hackme.txt'. sorry\n");
		else
			printf("cannot open 'teachme.txt'. sorry\n");
		return -1;
	}
	//получаем размер файла в переменную fSize
	fseek(inputFile,0L,SEEK_END);
	long fSize = ftell(inputFile);
	rewind(inputFile);


	int i=0;
	while(i<fSize/2){//it might truncate one symbol at the end, but it's simple
		int currentBigram = fgetc(inputFile) * 1000 + fgetc(inputFile);

		for(int j=0;j<SIZE;j++){//here we need a smarter way to search though array.
			if(outputBuffer[j]==0){ // если текущая ячейка равна нулю, то значит больше значений в массиве нет и можно в неё записывать наше значение.
				outputBuffer[j] = 1000000 + currentBigram; // записываем биграмму и прибавляем миллион, что означает +1 в счетчик частоты данной биграммы
				break;
			}
			else if((outputBuffer[j]%1000000)==currentBigram){//если нашлась биграмма, то просто добавляем ещё один миллион и выходим из цикла
				outputBuffer[j] = outputBuffer[j] + 1000000; // +1 frequency
				break;
			}
		}
		i++;
	}
	fclose(inputFile);
	//sorting the array:
	//стандартная функция сортировки. ничего необычного.
	qsort(outputBuffer,SIZE,sizeof(unsigned long),&cmpfunc);

	return 1;
}
/*
тут магии нет. одна бытовуха:
1.открываем один файл для записи и другой для чтения.

*/
int hackEncText(unsigned long bigramBufferPlain[],unsigned long bigramBufferHack[]){
	FILE* outputFile;
	outputFile = fopen("hacked.txt","w+");
	if(outputFile == NULL){
		printf("cannot write output file 'hacked.txt'. sorry\n");
		return -1;
	}
	FILE* inputFile;
	inputFile = fopen("hackme.txt","rb");
	if(inputFile == NULL){
		printf("cannot open input file 'hackme.txt'. sorry\n");
		return -1;
	}
	fseek(inputFile,0L,SEEK_END);
	long fSize = ftell(inputFile);
	rewind(inputFile);

	int i=0;
	while(i<fSize/2){//цикл немного похож на аналогичный в функции анализа
		int bigram = fgetc(inputFile)*1000 + fgetc(inputFile); // точно так же считаем биграмму в переменную

		int hackFreq = 0; //сюда будем заносить частоту биграммы для шифрованного текста.
		for(int j=0;j<SIZE;j++){
			if((bigramBufferHack[j]%1000000)==bigram){//если биграмма в массиве шифрованных биграмм нашлась, то
				hackFreq = bigramBufferHack[j]/1000000;//извлекаем из ячейки частоту для данной биграммы, просто поделив значение на миллион
				break;
			}
		}
		if(hackFreq){//проверяем если не ноль, и
			int newBigram = findNearestBigram(hackFreq,bigramBufferPlain);//ищем по значению частоты hackFreq аналогичную биграмму в нашем обычном тексте.
			writeBigram(outputFile,newBigram);//и всё: записываем её в файл.
		}
		else
			writeBigram(outputFile,044044);//если ничего не нашли, то записываем две запятых, можно смайлики записывать.
		i++;

	}
	fclose(outputFile);
	fclose(inputFile);
	return 1;
}
int main(){
	//there are 1296 combinations of pair out of 36 symbols
	unsigned long bigramBufferPlain[SIZE];	//bigram stored as number: 1234 111 222
											//				   		   freq  A   B
											// freq = bigramBuffer / 1000000
											// A = bigramBuffer / 1000 % 1000
											// B = bigramBuffer % 1000
	//
	//кароч, это два буфера: bigramBufferPlain - для хранения биграмм и их частот для обычного текста
	//bigramBufferHack - для закодированного текста, который нужно сломать. название намекает какбэ
	//биграммы храняться вместе с их частотами: миллионы - это частота биграммы, тысячи - это первый символ, а сотни - это второй символ.
	//картинка выше немного поясняет
	unsigned long bigramBufferHack[SIZE];

	//заполняем их нулями, чтобы убрать лишний мусор из ячеек:
	for(int i=0;i<SIZE;i++){
		bigramBufferPlain[i]=0;
		bigramBufferHack[i]=0;
	}


	analyzeInputText(0,bigramBufferPlain); //read input, count frequencies and write them into bigramBuffer for plain text
	analyzeInputText(1,bigramBufferHack);

	hackEncText(bigramBufferPlain,bigramBufferHack);

	return 0;

}