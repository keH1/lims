#!/usr/bin/expect
spawn /home/user/Linux/forticlient/forticlientsslvpn/64bit/forticlientsslvpn_cli --server vpn-dc.mos.ru:10443 --vpnuser netyosovia --pkcs12 netyosovia.pfx --keepalive
expect "Password for VPN:"
send "ILOVE-volley1975\r"
expect "Password for PKCS#12:"
send "soM@^4gK:uhn\r"
expect "*(Y/N)"
send "Y\r"
interact