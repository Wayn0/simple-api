#!/bin/bash

RED='\033[0;41;30m'
STD='\033[0;0;39m'

show_menu() {
	clear
	echo "~~~~~~~~~~~~~~~~~~~~~~"	
	echo "~ SIMPLE API MANAGER ~"
	echo "~~~~~~~~~~~~~~~~~~~~~~"
	echo "1. Install"
	echo "2. Backup"
	echo "3. Exit"
}

read_options(){
	local choice
	read -p "Enter choice [ 1 - 3] " choice
	case $choice in
		1) install ;;
		2) backup ;;
		3) exit 0;;
		*) echo -e "${RED}Error...${STD}" && sleep 2
	esac
}

install() {
    clear
    echo "starting installer"
    sleep 2
}

backup() {
    clear
    echo "running backup"
    sleep2
}

# Prevent usual exits ctrl-{c,z}
trap '' SIGINT SIGQUIT SIGTSTP
 
while true
do
	show_menu
	read_options
done