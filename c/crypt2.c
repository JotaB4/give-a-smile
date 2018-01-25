#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>//library to help parsing the command line arguments

#define HELP "Help:\n-k	generate new keys: 'key1.txt', 'key2.txt' and encrypt file\n-e	encrypt file 'plain.txt' \n-d	decrypt file 'encrypted.txt'\n"
//to avoid troubles with unicode, we use cp1251 and decimal codes to store letters
//symbols that are not in А-Я range we skip and write to the output file without encrypting
//
//some globals:
unsigned char key1[36];
unsigned char key2[36];
//
int generateKeyFiles(){
	unsigned char abc1[36]; //we use char type to be sure that only 1 byte will be written
	abc1[0]= 168; // yo, Ё
	abc1[1]= 32;  //  space
	abc1[2] = 46; // dot
	abc1[3] = 44; // comma
	for(int i =4, c = 192; i<36;i++){
		abc1[i] = c++;
	}
	//shuffle the alphabet: 
	//pretty ugly, huh?
	for(int j=0;j<20;j++){
		for(int i = 0;i<36;i++){
			int r = rand()%36;
			char tmp = abc1[i];
			abc1[i] = abc1[r];
			abc1[r] = tmp;
		}
	}
	char abc2[36];
	//crazy russian random:
	for(int i =0; i<36;i++){
		abc2[i] = abc1[i];
	}
	for(int j=0;j<20;j++){
		for(int i = 0;i<36;i++){
			int r = rand()%36;
			char tmp = abc2[i];
			abc2[i] = abc2[r];
			abc2[r] = tmp;
		}
	}
	FILE* keyFile1;
	keyFile1 = fopen("key1.txt","w+");
	if(keyFile1 != NULL){
		for(int i =0; i<36;i++){
			fputc(abc1[i],keyFile1);
		}
		printf("\nkey file 'key1.txt' is generated");
		fclose(keyFile1);
	}
	else {
		printf("\nsorry. cannot create 'key1.txt'");
		return -1;
	}
	
	FILE* keyFile2;
	keyFile2 = fopen("key2.txt","w+");
	if(keyFile2 != NULL){
		for(int i =0; i<36;i++){
			fputc(abc2[i],keyFile2);
		}
		printf("\nkey file 'key2.txt' is generated");
		fclose(keyFile2);
	}
	else {
		printf("\nsorry. cannot create 'key2.txt'");
		return -1;
	}
	return 1; //return non-zero to indicate that everything's fine	
}

int readKeys(){
	FILE* inputKeyFile1;
	inputKeyFile1 = fopen("key1.txt","r");
	if(inputKeyFile1 != NULL){
		for(int i=0;i<36;i++) //we know that the file content is 36 chars, checking of EOF isn't necessary
			key1[i] = fgetc(inputKeyFile1);

		fclose(inputKeyFile1);
	}
	else {
		printf("\nsorry. cannot open 'key1.txt'");
		return -1;
	}
	//i know this repeats the previous code, but then again the simple is better than the complex
	FILE* inputKeyFile2;
	inputKeyFile2 = fopen("key2.txt","r");
	if(inputKeyFile2 != NULL){
		for(int i=0;i<36;i++) 
			key2[i] = fgetc(inputKeyFile2);

		fclose(inputKeyFile2);
	}
	else {
		printf("\nsorry. cannot open 'key2.txt'");
		return -1;
	}
	return 1; //return non-zero to indicate that everything's fine	
}

int getMatrixIndex(unsigned char inputChar, int* row, int* col,int keyNum){//keyNum is for selecting key1 or key2
	int c=0;
	// turn 'abc' into 'ABC':
	if(inputChar > 223 && inputChar <= 255)
		inputChar = inputChar - 32;
	else if(inputChar == 184)
		inputChar = 168;

	//return row and column:
	for(int i=0;i<6;i++)
		for(int j=0;j<6;j++){
			unsigned char sym = (keyNum==1)? key1[c]:key2[c];
			if(inputChar == sym){
				*row = i;
				*col = j;
				return 1;
			}
			c++;
		}


	return 0; //0 - char is not found in matrix
}

int main(int argc, char* argv[]){
//parsing the argumets of the program:
	int opt;
    bool ENC_MODE = false;
    bool DECR_MODE = false;
    bool KEYS = false;
    if(argc<=1){
    	printf("please, specify the mode\nuse -h for help\n");
    	return -1;
    }
    while ((opt = getopt(argc, argv, "edkh")) != -1) {
        switch (opt) {
        case 'e': 
			if(DECR_MODE){
        		printf("\nyou cannot encrypt and decrypt at the same time.\n please specify only one mode");
        		return -1;
        	}
        	ENC_MODE = true;
        	break;
        case 'd':  
        	if(ENC_MODE){
        		printf("\nyou cannot encrypt and decrypt at the same time.\n please specify only one mode");
        		return -1;
        	}
        	DECR_MODE = true; 
        	break;
        case 'k': 
        	KEYS = true;
        	ENC_MODE = true;
        	break;
        case 'h': 
        	printf(HELP);
        	return 0;
        	break;
        default:
            printf(HELP);
        	return -1;
        }
    }
//end of parsing the arguments
    //
	if(KEYS && ENC_MODE)
		generateKeyFiles();
	
	readKeys();//read into global key1 and key2

//open file and read the content in the buffer:
	unsigned char* inputText; 
	unsigned char* outputText;
	
	int inputSize = 0;
	FILE* inputTextFile;

	if(ENC_MODE)
		inputTextFile = fopen("plain.txt","r");
	else
		inputTextFile = fopen("encrypted.txt","r");

	if(inputTextFile != NULL){
		//getting size of the input file
		fseek(inputTextFile, 0L, SEEK_END);
		int fileSize = ftell(inputTextFile);
		rewind(inputTextFile);
		inputSize = ((fileSize % 2) && ENC_MODE) ? fileSize + 1 : fileSize; //add extra byte for to add space later
				
		//using this size to allocate enough memory
		inputText = (unsigned char *)malloc(sizeof(unsigned char) * inputSize);
		if(inputText == NULL){
			printf("cannot allocate memory for input buffer\n");
			return -1;
		}
		outputText = (unsigned char *)malloc(sizeof(unsigned char) * inputSize);
		if(outputText == NULL){
			printf("cannot allocate memory for output buffer\n");
			return -1;
		}
		//
		//we have to check the input text for eveness and add extra space if needed
		for(int i=0;i<fileSize;i++){
			inputText[i] = fgetc(inputTextFile);
		}
		if(fileSize%2 && ENC_MODE)
			inputText[fileSize]=32;
    	
    	fclose(inputTextFile);
	}
	else {
		if(ENC_MODE)
			printf("\nsorry. cannot open 'plain.txt'");
		else
			printf("\nsorry. cannot open 'encrypted.txt'");
		return -1;
	}
//end of reading input file
	//
//here goes encrypting/decrypting proccess:	
	int row1=0;
	int col1=0;
	int row2=0;
	int col2=0;
	for(int k=0;k<inputSize;k=k+2){//we work with two chars at one cycle
		//get row and column of the first char in the input:
		int fSkipChar = 0;
		if(!getMatrixIndex(inputText[k],&row1,&col1,1))
			fSkipChar = 1;//if the symbol is not found in our matrix, write it as is
		
		if(inputText[k] == inputText[k+1] && ENC_MODE)
			inputText[k+1] = 218; //replacing for the hard sign if the same letters are in the bigram
		
		//now the same for the second one:	
		if(!getMatrixIndex(inputText[k+1],&row2,&col2,2))
			fSkipChar = 1;
		if(col1==col2 && (inputText[k] != inputText[k+1])){
			 //shifting rows if the letters in the same column
			if(ENC_MODE){ //when encrypting we shift the rows down
				row1 = (row1==5)? 0:row1+1;
				row2 = (row2==5)? 0:row2+1;
			}
			else { // when decrypting we shift the rows up
				row1 = (row1==0)? 5:row1-1;
				row2 = (row2==0)? 5:row2-1;
			}
		}
						
		if(!fSkipChar){
			outputText[k] = key1[(row1*6 + col2)];
			outputText[k+1] = key2[(row2*6 + col1)];
		}
		else{
			outputText[k] = inputText[k];
			outputText[k+1] = inputText[k+1];
		}
		}
//end of encrypting/decrypting
	//writing it to the file:

	FILE* outputFile;
	if(ENC_MODE)
		outputFile = fopen("encrypted.txt","w+");
	if(DECR_MODE) 
		outputFile = fopen("decrypted.txt","w+");
	
	if(outputFile != NULL)
		for(int i =0; i<inputSize;i++)
			fputc(outputText[i],outputFile);
	else {
		printf("\nsorry. cannot create output file");
		return -1;
	}
	printf("\noutput file is generated");
	fclose(outputFile);

	free(inputText);
	free(outputText);
return 0;
}
