#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main(int argc, char const* argv[])
{
    int i;
    char cmd[1024]={0};

    for (i=1; i<argc; i++) {
        if( i > 1 ){
             strcat( cmd, " ");
        }
        strcat( cmd, argv[i] );
    }
    if( cmd[0] && system(cmd) ){
        return 1;
    }
    return 0;
}