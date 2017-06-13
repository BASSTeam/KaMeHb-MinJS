/*
    Author:     Влад KaMeHb Марченко
    Version:    1.0-f
    ToDo:       --
*/
document.addEventListener("DOMContentLoaded", function(){
    var currentDir = null;
    fs = {
        'getCurrentFName' : function(onlyName){
            var fname = null;
            try {
                eval('+');
            } catch(e){
                fname = e.stack.split('.getCurrentFName (')[1].match(/(.*):\d*:\d*/)[1];
                if(onlyName){
                    fname = fs.splitDirName(fname)[1];
                }
            }
            return fname;
        },
        'getCurrentDir' : function(){
            if (currentDir !== null){
                return currentDir;
            } else {
                return fs.splitDirName(fs.getCurrentFName())[0];
            }
        },
        'cd' : function(dir){
            currentDir = dir;
        },
        'splitDirName' : function(str){
            var result = ['',''], tmp = str.split('/');
            result[1] = tmp.pop();
            result[0] = tmp.join('/') + '/';
            if (result[0] == ''){
                result[0] = '/';
            }
            return result;
        }
    };
});