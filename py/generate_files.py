for i in range(7):
    file = open('./steps/step_'+str(i+100),'w')
    file.write(str(i))
    file.close()
