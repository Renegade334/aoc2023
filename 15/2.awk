#!/usr/bin/awk -f

BEGIN {
	FS="[-=]"
	RS="[,\n]"
	for (b=0;b<256;b++) byte[sprintf("%c",b)]=b
}

END {
	sum=0
	for (k in boxes) {
		split(k,a,SUBSEP)
		sum+=(a[1]+1)*a[2]*lenses[boxes[k]]
	}
	print sum
}

function hash(key) {
	if (key in hashes) return hashes[key]
	h=0
	split(key,chars,"")
	for (c=1;c<=length(chars);c++) h=((h+byte[chars[c]])*17)%256
	return hashes[key]=h
}

{
	box=hash($1)
	if (length($2)) {
		if (!($1 in lenses)) {
			for (i=1;(box,i) in boxes;i++) continue
			boxes[box,i]=$1
		}
		lenses[$1]=$2
	}
	else if ($1 in lenses) {
		delete lenses[$1]
		for (i=1;(box,i) in boxes;i++) if (boxes[box,i]==$1) break
		while ((box,++i) in boxes) boxes[box,i-1]=boxes[box,i]
		delete boxes[box,i-1]
	}
}
