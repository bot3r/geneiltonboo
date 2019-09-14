<?php
// Prevent PHP from stopping the script after 30 sec
set_time_limit(0);
$channel  = '#brazil';
$nickname = 'geneilton01';
$master   = 'geneilton01';
// Opening the socket to the Rizon network
$socket = fsockopen("irc.chknet.net", 6667);
// Send auth info
//fputs($socket,"USER HammsterBot hammst3r.de HammsterBot :HammsterBot\n");
fputs($socket, "USER " . $nickname . " 0 * :" . $master . "'_\n");
fputs($socket, "NICK " . $nickname . "\n");
// Join channel
fputs($socket, "JOIN " . $channel . "\n");
// Force an endless while
while (1) {
    // Continue the rest of the script here
    while ($data = fgets($socket, 128)) {
        echo $data;
        flush();
        
        // Separate all data
        $ex = explode(' ', $data);
        
        // Send PONG back to the server
        if ($ex[0] == "PING") {
            fputs($socket, "PONG " . $ex[1] . "\n");
        }
        
        // executes chat command
        if ($ex[0] != 'PING' && ISSET($ex[3])) {
            $command = str_replace(array(
                chr(10),
                chr(13)
            ), '', $ex[3]);
            if ($command == ":!alive?") {
                fputs($socket, "PRIVMSG " . $channel . " :whazzup? \n");
            }
            if ($command == ":!time") {
                fputs($socket, "PRIVMSG " . $channel . " :" . date(DATE_RFC2822) . " \n");
            }
            if ($command == ":!help") {
                fputs($socket, "PRIVMSG " . $channel . " :Hambot phpIRCbot v0.1 commands. \n");
                fputs($socket, "PRIVMSG " . $channel . " :!alivegahgaakja?, !time, !slave, !calor, !meme !meat \n");
            }
            if ($command == ":!slave") {
                
                $parts = explode("!", $ex[0]);
                $user  = substr($parts['0'], 1);
                
                if ($user == $master)
                    fputs($socket, "PRIVMSG " . $channel . "");
                else
                    fputs($socket, "PRIVMSG " . $channel . "get lost " . $user . "");
            }
            if ($command == ":!test") {
                fputs($socket, "PRIVMSG " . $channel . " :value0 " . $ex[0] . ", value1 " . $ex[1] . ",value2 " . $ex[2] . ",value3 " . $ex[3] . "\n");
            }
            if ($command == ":!calor") {
                $joke = json_decode(file_get_contents('nossa que calor slkkk affs', true));
                fputs($socket, "PRIVMSG " . $channel . " :" . $joke->value->joke . " \n");
            }
            if ($command == ":!meme") {
                $meme = file_get_contents('');
                fputs($socket, "PRIVMSG " . $channel . " :" . $meme . " \n");
            }
            if ($command == ":!meat") {
                $meat = file_get_contents('');
                $meat = explode(" ", $meat);
                $meat = substr($meat['0'], 2);
                fputs($socket, "PRIVMSG " . $channel . " :" . $meat . " \n");
            }
        }
    }
}
?>
