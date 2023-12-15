#!/usr/bin/awk -f

BEGIN {
	FS=""
	RS="[,\n]"
	for (b=0;b<256;b++) byte[sprintf("%c",b)]=b
}

END {
	print sum
}

{
	hash=0
	for (i=1;i<=NF;i++) hash=((hash+byte[$i])*17)%256
	sum+=hash
}
