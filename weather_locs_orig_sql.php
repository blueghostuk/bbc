<?php
require('includes/DB_Connection.php');
require('/home/blueghos/db.php');
$dbase   	= 'blueghos_bbc';
$Database 	= new DB_Connection();
$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0201", "Abbotsbury", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1654", "Aberdare", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0001", "Aberdeen", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0196", "Abergwesyn", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0180", "Aberporth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0193", "Abersoch", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0203", "Aberystwyth", "1")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0002", "Abingdon", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1241", "Accrington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0194", "Acharn", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2092", "Aintree", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0003", "Airdrie", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0858", "Aldershot", "1")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1349", "Alfreton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0199", "Alston", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0198", "Altnaharra", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0004", "Altrincham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0195", "Amesbury", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0197", "Amlwch", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0200", "Andover", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1053", "Arbroath", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2089", "Argyll", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1129", "Arnold", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2088", "Arran", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0005", "Ashford", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0589", "Ashington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0632", "Ashton-in-Makerfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0790", "Ashton-under-Lyne", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0166", "Aviemore", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0204", "Aylesbury", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0006", "Ayr", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2087", "Ayrshire", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0205", "Bala", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1540", "Ballymena", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0209", "Banbury", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0007", "Banchory", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0008", "Bangor", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0913", "Banstead", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0287", "Bardesy", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0289", "Barmouth", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0563", "Barnet", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0009", "Barnsley", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1463", "Barnstaple", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1577", "Barrow-in-Furness", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0010", "Barry", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0518", "Basildon", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0011", "Basingstoke", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0012", "Bath", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0013", "Batley", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0829", "Bearsden", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0726", "Bebington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0014", "Bedford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0948", "Bedworth", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1329", "Beeston", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0015", "Belfast", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0016", "Bellshill", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0914", "Benfleet", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0826", "Bentley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1161", "Beverley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2098", "Beverly", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0216", "Bexhill", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1719", "Bexhill-on-Sea", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0850", "Bexley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1099", "Bicester", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1146", "Billericay", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1123", "Billingham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0017", "Birkenhead", "1")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0018", "Birmingham", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1150", "Bishop Auckland", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0739", "Bishop\'s Stortford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1232", "Bishopbriggs", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1036", "Blackburn", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0019", "Blackpool", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1331", "Bletchley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0689", "Blyth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0210", "Bodenham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1105", "Bognor Regis", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0020", "Bolton", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1575", "Bootle", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0810", "Borehamwood", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1701", "Boston", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0172", "Boulmer", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0212", "Bourne", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0021", "Bournemouth", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0022", "Bracknell", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0023", "Bradford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1343", "Braintree", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0827", "Bramhall", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0229", "Brawdy", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0206", "Brecon", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0624", "Brent", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1344", "Brentwood", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1730", "Bridgend", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1216", "Bridgwater", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0024", "Bridlington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0174", "Bridlington Mrsc", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1159", "Brighouse", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0323", "Brighstone", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0215", "Brighton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0025", "Bristol", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0182", "Brize Norton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0359", "Broadstairs", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0828", "Bromborough", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1649", "Bromley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0026", "Bromsgrove", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0208", "Bruton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0207", "Bude", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1770", "Burgess Hill", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1137", "Burnley", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1786", "Burntwood", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0027", "Burton upon Trent", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0528", "Burton-on-Trent", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0626", "Bury", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0213", "Bury St Edmunds", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0211", "Butter Tubs Pass", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0214", "Buxton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0738", "Caerphilly", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0217", "Cairnryan", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0670", "Camberley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0185", "Camborne", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0028", "Cambridge", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1127", "Camden", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1025", "Cannock", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0029", "Canterbury", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0416", "Canvey Island", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0327", "Cape Wrath", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0030", "Cardiff", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0326", "Cardigan Bay", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0226", "Carlisle", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1026", "Carlton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0345", "Carrickfergus", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1915", "Castle Combe", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1624", "Castleford", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1790", "Central Milton Keynes", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0824", "Chadderton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0324", "Chale", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1531", "Chapeltown", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0780", "Chatham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0031", "Chelmsford", "1")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0032", "Cheltenham", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1330", "Cheshunt", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0033", "Chester", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0823", "Chester-le-Street", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0034", "Chesterfield", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0899", "Chichester", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1715", "Chippenham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0629", "Chipping Sodbury", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0227", "Chivenor", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0825", "Chorley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1138", "Christchurch", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0219", "Clacton", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0648", "Clacton-on-Sea", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0373", "Cleethorpes", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0035", "Clydebank", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0681", "Coalville", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0036", "Coatbridge", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0037", "Colchester", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0224", "Coleraine", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0218", "Colwyn Bay", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0849", "Congleton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0225", "Copplestone", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1490", "Corby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0322", "Cottesmore", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0038", "Coventry", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0221", "Craigencallie", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0493", "Cramlington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0816", "Crawley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0220", "Crew Green", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0228", "Crewe", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0223", "Crewkerne", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0222", "Cromer", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1230", "Crosby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1425", "Croydon", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0186", "Culdrose", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0820", "Cumbernauld", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1590", "Cwmbran", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0039", "Dalkeith", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0817", "Darlington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0745", "Dartford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1037", "Darwen", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1445", "Deal", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1788", "Denton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0040", "Derby", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0230", "Devauden", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0727", "Dewsbury", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0041", "Doncaster", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0042", "Dover", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2093", "Down Royal", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0043", "Dronfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0932", "Droylsden", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0044", "Dudley", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0663", "Dumbarton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0360", "Dumfries", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0045", "Dundee", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0046", "Dunfermline", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0231", "Dunkeswell Arpt", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1762", "Dunstable", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0047", "Durham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0048", "Dyce", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1426", "Ealing", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0232", "Easington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0904", "East Grinstead", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0049", "East Kilbride", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0050", "East Midlands", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1139", "Eastbourne", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0051", "Eastleigh", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0822", "Eccles", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0630", "Ecclesfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0052", "Edinburgh", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1124", "Egham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1546", "Ellesmere Port", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0053", "Ellon", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1561", "Enfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1228", "Epsom", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0482", "Esher", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0169", "Eskdalemuir", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0233", "Eskmeals", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0054", "Exeter", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1354", "Exmouth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0234", "Exton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0235", "Fairlight", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0055", "Falkirk", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0056", "Fareham", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0057", "Farnborough", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1028", "Farnham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1132", "Farnworth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0573", "Felixstowe", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0728", "Felling", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1338", "Ferndown", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0236", "Ffestiniog", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0531", "Fleet", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0661", "Fleetwood", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0058", "Folkestone", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0679", "Formby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0059", "Foveran", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0237", "Freshwater", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1729", "Frimley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1642", "Frome", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2086", "Galloway", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0243", "Garvagh", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0060", "Gateshead", "1")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0625", "Gillingham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0061", "Glasgow", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0240", "Glen Orrin", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0238", "Glenanne", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0775", "Glenrothes", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0714", "Glossop", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0062", "Gloucester", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0239", "Godalming", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2094", "Goodwood", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1532", "Gosforth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0413", "Gosport", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0729", "Grantham", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0241", "Grassington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1430", "Gravesend", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1205", "Grays", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0819", "Greasby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0628", "Great Sankey", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0242", "Great Wakering", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0063", "Great Yarmouth", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1728", "Greenock", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1428", "Greenwich", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0064", "Grimsby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0065", "Guernsey Island", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1727", "Guildford", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1725", "Hackney", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1229", "Halesowen", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1362", "Halifax", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0066", "Hamilton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1726", "Hammersmith", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1227", "Haringey", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0244", "Harlow", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0634", "Harpenden", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1328", "Harrogate", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1471", "Harrow", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0067", "Hartlepool", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0929", "Hastings", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1530", "Hatfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0068", "Havant", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0526", "Havering", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1632", "Hawarden", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0249", "Hay-On-Wye", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2095", "Haydock Park", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0631", "Haywards Heath", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0456", "Heanor", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0527", "Hemel Hempstead", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0179", "Hemsby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0245", "Hereford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0651", "Herne Bay", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0189", "Herstmonceux", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1030", "Heswall", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0500", "Heywood", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0474", "High Wycombe", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0814", "Hillingdon", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0190", "Hillsborough", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0980", "Hinckley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1732", "Hindley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1789", "Hitchin", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1154", "Hoddesdon", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0246", "Holywell", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1303", "Horsham", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0247", "Horst Green", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0069", "Hounslow", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1738", "Hove", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1231", "Hoylake", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1332", "Hucknall", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0070", "Huddersfield", "1")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0248", "Hunstanton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0930", "Huyton-with-Roby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1608", "Hyde", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1164", "Ilford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1449", "Ilkeston", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0250", "Inverness", "1")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0071", "Inverurie", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0072", "Ipswich", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0576", "Irvine", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1764", "Islington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0690", "Jarrow", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0251", "Jersey", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0939", "Keighley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2096", "Kempton Park", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1177", "Kendal", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0742", "Kenilworth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1844", "Kensington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1894", "Kent", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0254", "Kettering", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0073", "Kidderminster", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0532", "Kidsgrove", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0074", "Kilmarnock", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0504", "King\'s Lynn", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0075", "Kingston upon Hull", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0770", "Kingston upon Thames", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0076", "Kingswood", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0167", "Kinloss", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0873", "Kirkby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0732", "Kirkby-in-Ashfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1279", "Kirkcaldy", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0252", "Kirkstone Pass", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0164", "Kirkwall Airport", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0253", "Knighton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0926", "Lambeth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0581", "Lancaster", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0077", "Larne", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1275", "Leatherhead", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0078", "Leeds", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0173", "Leeming", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0079", "Leicester", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1126", "Leigh", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0257", "Leighton Buzzard", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1529", "Leighton-Linslade", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0080", "Leith", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0081", "Lerwick", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0712", "Letchworth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0170", "Leuchars", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1427", "Lewisham", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0605", "Leyland", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1423", "Lichfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1087", "Lincoln", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0082", "Lisburn", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0260", "Littlehampton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0083", "Liverpool", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0084", "Livingston", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0259", "Llandrindod Wells", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0256", "Llandudno", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1305", "Llanelli", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0263", "Llangadog", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0264", "Llangoed", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0255", "Llanidloes", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0262", "Llanwrtyd Wells", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1630", "Locks Heath", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0085", "London", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0086", "Londonderry", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0087", "Long Eaton", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0680", "Loughborough", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1269", "Loughton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0399", "Lowestoft", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0261", "Lulworth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1432", "Lurgan", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0088", "Luton", "1")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0183", "Lyneham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0258", "Lynmouth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0272", "Mablethorpe", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0089", "MacClesfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0831", "Maghull", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0090", "Maidenhead", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0091", "Maidstone", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0269", "Malvern", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0092", "Manchester", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1031", "Mangotsfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0498", "Mansfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0184", "Manston", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1051", "Margate", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0271", "Marham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1079", "Melton Mowbray", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0273", "Merthyr", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1046", "Merthyr Tydfil", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1128", "Merton", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0093", "Middlesbrough", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1431", "Middleton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0094", "Mildenhall", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0267", "Milnthorpe", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0268", "Milton Keynes", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0266", "Minehead", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0270", "Monmouth", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0390", "Morecambe", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1527", "Morley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0095", "Motherwell", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0096", "Musselburgh", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1707", "Neath", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1737", "Nelson", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0278", "New Quay", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0887", "Newark", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0276", "Newark On Trent", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0529", "Newburn", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0097", "Newbury", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0098", "Newcastle", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1695", "Newcastle upon Tyne", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0818", "Newcastle-under-Lyme", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1327", "Newham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0274", "Newhaven", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0099", "Newport", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1794", "Newport (Dyfed)", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1792", "Newport (Isle of Wight)", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1797", "Newport (Shropshire)", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0830", "Newry", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1516", "Newton Abbot", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0556", "Newton Aycliffe", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0100", "Newtownabbey", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0341", "Newtownards", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0101", "North Berwick", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0275", "North Downs", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1586", "North Shields", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0102", "Northampton", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1551", "Northwich", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0103", "Norwich", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0104", "Nottingham", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0552", "Nuneaton", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0280", "Okehampton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0105", "Oldham", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0279", "Orford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0281", "Ormskirk", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0106", "Oxford", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0616", "Paignton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0107", "Paisley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0108", "Penarth", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0109", "Penicuik", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1032", "Penwortham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0286", "Penzance", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0285", "Perth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0110", "Peterborough", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0111", "Peterhead", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1113", "Peterlee", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0112", "Plymouth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0931", "Pontefract", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0889", "Pontypool", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0943", "Pontypridd", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0113", "Poole", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0114", "Port Talbot", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0115", "Portsmouth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0959", "Potters Bar", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1679", "Prescot", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0284", "Presteigne", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0282", "Preston", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0283", "Princetown", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0486", "Pudsey", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0430", "Radcliffe", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0288", "Raglan", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0116", "Ramsgate", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0832", "Rawtenstall", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0292", "Rayleigh", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0117", "Reading", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1324", "Redbridge", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0921", "Redcar", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1640", "Redditch", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0290", "Redesdale", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0606", "Redhill", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0118", "Reigate", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1429", "Rhondda", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0392", "Rhyl", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0291", "Rhyll", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0928", "Richmond-upon-Thames", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0119", "Rickmansworth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0120", "Rochdale", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1384", "Rochester", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1917", "Rockingham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0797", "Romford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2091", "Ross Shire", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0121", "Rotherham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1525", "Royal Leamington Spa", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1524", "Royal Tunbridge Wells", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1252", "Rugby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1323", "Rugeley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0122", "Rumney", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1783", "Runcorn", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1389", "Rushden", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0123", "Rutherglen", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0124", "Saint Anne/Alderney Island", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0125", "Saint Aubin/Jersey Island", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0126", "Saint Austell", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0191", "Saint Helena Island", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0127", "Saint Helens", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0187", "Saint Mawgan", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0128", "Saint Peter Port", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1484", "Sale", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0129", "Salford", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0301", "Salisbury", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0130", "Saltash", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0299", "Sandbach", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0131", "Scarborough", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0303", "Scilly", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0132", "Scunthorpe", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1112", "Seaham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1418", "Sevenoaks", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0177", "Shawbury", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0133", "Sheffield", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0134", "Shipley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0297", "Shipton", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0730", "Shirley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0296", "Shrewsbury", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1916", "Silverstone", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1289", "Sittingbourne", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1785", "Skelmersdale", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0298", "Skipton", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0325", "Skomer", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0135", "Slough", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0136", "Smethwick", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0295", "Snowdon", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1134", "Solihull", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0137", "South Shields", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0138", "Southampton", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1314", "Southend-on-Sea", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0139", "Southport", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1326", "Southwark", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0300", "Spalding", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0429", "St Albans", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0431", "St Helier", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1611", "Stafford", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1221", "Staines", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1212", "Stalybridge", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0731", "Staveley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0513", "Stevenage", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1565", "Stirling", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0140", "Stockport", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0302", "Stocks Resvr", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0141", "Stockton-on-Tees", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0142", "Stoke on Trent", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1115", "Stoke-on-Trent", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0143", "Stonehaven", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0165", "Stornoway", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1453", "Stourbridge", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0332", "Strait Of Dover", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0294", "Stratford On Avo", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1549", "Stratford-upon-Avon", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1027", "Stretford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1629", "Strood", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1066", "Stroud", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0144", "Sumburgh", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0145", "Sunderland", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0974", "Surbiton", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1110", "Sutton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1638", "Sutton Coldfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0787", "Sutton-in-Ashfield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0655", "Swadlincote", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0146", "Swansea", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0147", "Swindon", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0148", "Tamworth", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0309", "Taunton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0149", "Tavistock", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1024", "Teesside", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0305", "Telford", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0627", "Telford Dawley", "1")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0304", "Tenby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0330", "The Little Minch", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1325", "The Medway Towns", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0623", "The Potteries", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1731", "Thornaby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1631", "Thornaby-on-Tees", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1130", "Thornton/Cleveleys", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0168", "Tiree", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0307", "Tonbridge", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0150", "Torquay", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1131", "Totton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1787", "Tower Hamlets", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0308", "Trostan", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0151", "Trowbridge", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0306", "Tunbridge Wells", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX2090", "Tweedale", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0530", "Tyldesley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1523", "Tyneside", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0310", "Tywyn", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1528", "Urmston", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0175", "Valley", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0176", "Waddington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0311", "Wainfleet", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0152", "Wakefield", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1089", "Walkden", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0357", "Wallasey", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0586", "Wallsend", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0812", "Walsall", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0927", "Waltham Forest", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1226", "Wandsworth", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0153", "Warrington", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0319", "Warslow", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1347", "Warwick", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1696", "Washington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1300", "Waterlooville", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0924", "Watford", "2")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0181", "Wattisham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1388", "Wellingborough", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0314", "Wellington", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0316", "Welshpool", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0534", "Welwyn Garden City", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1158", "Wembley", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1029", "West Bridgford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1733", "West Bromwich", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0815", "Westminster", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0320", "Weston-Spr-Mare", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1628", "Weston-super-Mare", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0321", "Weybourne", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0154", "Weybridge", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0317", "Weymouth", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0312", "Whitby", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0488", "Whitefield", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0318", "Whitehall", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1045", "Whitehaven", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0315", "Whitland", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1093", "Whitley Bay", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0155", "Whitstable", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0313", "Wick", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1114", "Wickford", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0156", "Widnes", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0925", "Wigan", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1376", "Wigston", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1413", "Wilmslow", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0157", "Winchester", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0158", "Windsor", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0949", "Winsford", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1767", "Wisbech", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1186", "Wishaw", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0946", "Witham", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0178", "Wittering", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1067", "Woking", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0903", "Wokingham", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0159", "Wolverhampton", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0160", "Woodbridge", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0161", "Worcester", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1741", "Workington", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0710", "Worksop", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1526", "Worsley", "0")';$ins=$Database->DB_search($sql);

$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0639", "Worthing", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0821", "Wrexham", "2")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX1435", "Yeovil", "1")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0188", "Yeovilton", "0")';$ins=$Database->DB_search($sql);
$sql = 'INSERT INTO `weather_locs` ( `id`, `placename`, `level` ) VALUES ( "UKXX0162", "York", "2")';$ins=$Database->DB_search($sql);
?>