import sys
import io

this_text = io.StringIO()

print('before')
saved_output = sys.stdout
sys.stdout = this_text
import this
sys.stdout = saved_output
print('after')
full_this_text = this_text.getvalue()
full_this_text = full_this_text.splitlines()
this_text.close()
for i,line in enumerate(full_this_text):
    print(i,end=' ')
    txt_file = open('py_zen_' + str(i),'w')
    txt_file.write(line)
    txt_file.close()

