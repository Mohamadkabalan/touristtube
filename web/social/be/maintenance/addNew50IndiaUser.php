<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',1);
$database_name = "touristtube";


mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
//mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);

$userarray = array(array('Aakarshan', 'Achari', 'm', '1984-4-15', 'aakarshan.achari@yandex.com'),
array('Aanand', 'Adiga', 'm', '1979-8-17', 'aanand.adiga@yandex.com'),
array('Aarth', 'Agarwal', 'm', '1984-1-16', 'aarth.agarwal@yandex.com'),
array('Aarush', 'Kucchal', 'm', '1977-12-12', 'aarush.kucchal@yandex.com'),
array('Aashman', 'Bindal', 'm', '1979-10-1', 'aashman.bindal@yandex.com'),
array('Aatmaj', 'Tayal', 'm', '1985-12-2', 'aatmaj.tayal@yandex.com'),
array('Aayushmaan', 'Ahluwalia', 'm', '1972-11-28', 'aayushmaan.ahluwalia@yandex.com'),
array('Babala', 'Asan', 'm', '1977-6-23', 'babala.asan@yandex.com'),
array('Bajrang', 'Banerjee', 'm', '1961-11-20', 'bajrang.banerjee@yandex.com'),
array('Balamani', 'Bhat', 'm', '1988-7-4', 'balamani.bhat@yandex.com'),
array('Balaraj', 'Chaturvedi', 'm', '1978-8-22', 'balarajchatu@myway.com'),
array('Balbir', 'Chattopadhyay', 'm', '1964-11-28', 'balbirchatto@myway.com'),
array('Bheesham', 'Kaniyar', 'm', '1966-6-1', 'bheesham@myway.com'),
array('Bhupendra', 'Kerala', 'm', '1972-1-28', 'bhupendra72@myway.com'),
array('Dakshesh', 'Bandopadhyay ', 'm', '1985-12-27', 'dakshesh85@myway.com'),
array('Dandapaani', 'Bharadwaj', 'm', '1977-8-12', 'dandapaani77@myway.com'),
array('Darshan', 'Chopra', 'm', '1984-10-17', 'darshanchopra84@myway.com'),
array('Deepak', 'Desai', 'm', '1968-11-26', 'deepak68@myway.com'),
array('Phaninath', 'Dhawani', 'm', '1981-5-13', 'phaninath@myway.com'),
array('Gagandeep', 'Dwivedi', 'm', '1975-2-1', 'gagandeepdwivedi@myway.com'),
array('Gajrup', 'Ganaka', 'm', '1958-12-23', 'gajrupganaka@maiz.ca'),
array('Ganapati', 'Gandhi', 'm', '1985-9-23', 'ganapatigandhi@maiz.ca'),
array('Gangesh', 'Gupta', 'm', '1985-2-16', 'gangeshgupta@maiz.ca'),
array('Gaurav', 'Gowda', 'm', '1948-12-25', 'gauravgowda@maiz.ca'),
array('Haroon', 'Deshpande', 'm', '1979-1-27', 'haroondeshpande@maiz.ca'),
array('Havish', 'Johar', 'm', '1982-4-26', 'havishjohar@maiz.ca'),
array('Madhu', 'Mishra', 'm', '1980-7-13', 'madhumishra@maiz.ca'),
array('Mahaveer', 'Patel', 'm', '1978-10-9', 'mahaveer@maiz.ca'),
array('Milap', 'Pothuvaal', 'm', '1992-5-5', 'milap@maiz.ca'),
array('Munish', 'Saryuparin', 'm', '1970-9-6', 'munish@maiz.ca'),
array('Naagesh', 'Bhatti', 'm', '1960-1-2', 'naagesh@gmx'),
array('Nalesh', 'Pillai', 'm', '1972-8-14', 'nalesh.pillai@tutanota.de'),
array('Namish', 'Rajput', 'm', '1988-7-28', 'namish.rajput@tutanota.com'),
array('Namit', 'Rajput', 'm', '1991-2-25', 'namit.rajput@tutanota.com'),
array('Nandan', 'Sharma', 'm', '1974-8-20', 'nandan.sharma@tutanota.com'),
array('Narsimha', 'Singh', 'm', '1988-4-17', 'narsimha.singh@tutanota.com'),
array('Nishan', 'Sinha', 'm', '1975-10-16', 'nishan.sinha@tutanota.com'),
array('Nishipal', 'Tagore', 'm', '1993-5-6', 'nishipal@tutanota.com'),
array('Omanand', 'Talwar', 'm', '1990-4-26', 'omanand.talwar@tutanota.com'),
array('Omeshwar', 'Trivedi', 'm', '1989-1-10', 'omeshwar.trivedi@tutanota.com'),
array('Oojam', 'Verma', 'm', '1976-7-23', 'oojam.verma@tutamail.com'),
array('Paavak', 'Sinha', 'm', '1977-3-20', 'paavak@tutamail.com'),
array('Padminish', 'Tagore', 'm', '1978-8-17', 'padminish.tagore@tutamail.com'),
array('Parakram', 'Varman', 'm', '1979-6-20', 'parakram.varman@tutamail.com'),
array('Paramjeet', 'Yadav', 'm', '1980-3-2', 'paramjeet.yadav@tutamail.com'),
array('Saatvik', 'Sabyasachi', 'm', '1981-5-21', 'saatvik.sabyasachi@tutamail.com'),
array('Sadeepan', 'Shukla', 'm', '1982-10-11', 'sadeepan.shukla@tutamail.com'),
array('Saikiran', 'Kalari', 'm', '1983-8-4', 'saikiran.kalari@tutamail.com'),
array('Sakshum', 'Tandon', 'm', '1984-8-2', 'sakshum.tandon@tutamail.com'),
array('Taresh', 'Sinha', 'm', '1985-6-4', 'taresh.sinha@tutamail.com'));

$sql="SELECT * FROM `cms_users` WHERE `YourEmail` LIKE 'user@touristtube.com' AND `published` = 0 ORDER BY `cms_users`.`id` DESC LIMIT 0,50";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
$i=0;

 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $uarr= $userarray[$i];    
    $fname = $uarr[0];
    $lname = $uarr[1];
    $FullName = $fname.' '.$lname;
    $YourCountry = 'IN';
    $gender = 'M';
    if($uarr[2]=="f" || $uarr[2]=="F") $gender = 'F';
    $YourEmail = $uarr[4];
    $YourUserName = $uarr[4];
    $YourBday = $uarr[3];
    
    $sqr= "UPDATE `cms_users` SET `FullName`='$FullName',`fname`='$fname',`lname`='$lname',`gender`='$gender',`YourEmail`='$YourEmail',`YourCountry`='$YourCountry',`YourBday`='$YourBday',`YourUserName`='$YourUserName',`published`='1',`isChannel`='0' WHERE id=".$r['id'];
    mysql_query($sqr);
    $i++;
}