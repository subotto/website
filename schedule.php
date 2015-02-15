<?php

include_once "inclusions.php";

start_html("Turni");
make_header(true, "current");

start_box("Turni della 24 ore 2015");

paragraph(bold("I turni non sono ancora definitivi, e potrebbero subire variazioni."));

?>

<table id="tabella">
<thead>
<tr><th>Orario</th><th>Matematici</th><th>Fisici</th></tr>
</thead><tbody>

<tr><td>8:00 - 8:30</td><td>Andrea Parma, Luigi Pagano</td><td>Ludovico Pontiggia, Michele Maiolani</td></tr>
<tr><td>8:30 - 9:00</td><td>Alexandra Baranova, Giovanni Barbarino</td><td>Giacomo Canepa, Raffaele Sarnataro</td></tr>
<tr><td>9:00 - 9:30</td><td>Manuel Berbenni, Ludovico Pernazza</td><td>Achille Mauri, Piero Lafiosca</td></tr>
<tr><td>9:30 - 10:00</td><td>Matteo Barucco, Francesco Grotto</td><td>Giacomo De Palma, Enis Belgacem</td></tr>
<tr><td>10:00 - 10:30</td><td>Francesco Morosi, Edoardo Galfré</td><td>Ilaria Morresi, Anna Zago</td></tr>
<tr><td>10:30 - 11:00</td><td>Emanuele Tron, Luca Ghidelli</td><td>Achille Mauri, Martino Ugolini</td></tr>
<tr><td>11:00 - 11:30</td><td>Claudio Afeltra, Justin Lacini</td><td>Martina Bottachiari, Niccolò Lusiani</td></tr>
<tr><td>11:30 - 12:00</td><td>Alessandro Pigati, Andrea Bianchi</td><td>Davide Decataldo, Enrico Dardanis</td></tr>
<tr><td>12:00 - 12:30</td><td>Valentino Liu, Cristoforo Caffi</td><td>Carmelo Mordini, Luca Baroni</td></tr>
<tr><td>12:30 - 13:00</td><td>Giada Franz, Federico Glaudo</td><td>Matteo Becchi, Filippo Revello</td></tr>
<tr><td>13:00 - 13:30</td><td>Matteo Migliorini, Manuele Cusumano</td><td>Roberto Ribatti, Andrea Luzio</td></tr>
<tr><td>13:30 - 14:00</td><td>Roberto Pagaria, Dario Balboni</td><td>Paolo Abiuso, Federica Surace</td></tr>
<tr><td>14:00 - 14:30</td><td>Dario Ascari, Andrea Marino</td><td>Michele Fava, Maria Caiazza</td></tr>
<tr><td>14:30 - 15:00</td><td>Giovanni Italiano, Giulia Trevisan</td><td>Marco Costa, Alessandro Candido</td></tr>
<tr><td>15:00 - 15:30</td><td>Gianluca Tasinato, Umberto Pappalettera</td><td>Gabriele Pulvirenti, Davide Burgio</td></tr>
<tr><td>15:30 - 16:00</td><td>Marco Barberis, Daniele Semola</td><td>Vasco Cavina, Simone Biasco</td></tr>
<tr><td>16:00 - 16:30</td><td>Matteo Verzobio, Federico Scavia</td><td>Giulio Mandorli, Giuliano Chiriacò</td></tr>
<tr><td>16:30 - 17:00</td><td>Alessandro Iraci, Ugo Bindini</td><td>Davide Napoli, Marco Cilibrasi</td></tr>
<tr><td>17:00 - 17:30</td><td>Giovanni Paolini, Giovanni Mascellani</td><td>Emanuele Penocchio, Tommaso Stefanini</td></tr>
<tr><td>17:30 - 18:00</td><td>Lorenzo Benedini, Marco Trevisiol</td><td>Matteo Rovere, Alberto Bordin</td></tr>
<tr><td>18:00 - 18:30</td><td>Giulio Bresciani, Leonardo Tolomeo</td><td>Stanislao Zompì, Giovanni Tonolo</td></tr>
<tr><td>18:30 - 19:00</td><td>Alice Cortinovis, Davide Lofano</td><td>Olmo Cerri, Alessandro Podo</td></tr>
<tr><td>19:00 - 19:30</td><td>Julian Demeio, Matteo De Ceglie</td><td>Cristina Bellotti, Alexia Tiberi</td></tr>
<tr><td>19:30 - 20:00</td><td>Nicola Picenni, Clara Antonucci</td><td>Cosimo Paravano, Giacomo Ranieri</td></tr>

</tbody>
</table>

<?php

start_box("Turni della 24 ore 2014");

function boris() {
	$r = rand(0,1);
	if ( $r == 0 ) return "Loris";
	else return "Boris";
}

function lagnasco() {
	$r = rand(0,1);
	if ( $r == 0 ) return "Bagnasco";
	else return "Lagnasco";
}

function boris_lagnasco() {
	echo boris()." ".lagnasco();
}

?>

<table id="tabella">
<thead>
<tr><th>Orario</th><th>Matematici</th><th>Fisici</th></tr>
</thead><tbody>


<tr><td>8.00 - 8.30</td><td>Luigi Pagano, Leonardo Tolomeo</td><td>Matteo Rovere, Francesco Cannizzaro</td></tr>
<tr><td>8.30 - 9.00</td><td>Filippo Callegaro, Federico Poloni</td><td>Andrea Stacchiotti, Enis Belgacem</td></tr>
<tr><td>9.00 - 9.20</td><td>Luca Minutillo Menga, Emanuele Tron</td><td>Achille Mauri, Fedor Getman</td></tr>
<tr><td>9.20 - 9.40</td><td>Francesco Florian, Claudio Afeltra</td><td>Niccolò Foppiani, Fabio Martini</td></tr>
<tr><td>9.40 - 10.00</td><td>Justin Lacini, Francesco Grotto</td><td>Andrea Stacchiotti, Matteo Ippoliti</td></tr>
<tr><td>10.00 - 10.30</td><td>Roberto Pagaria, Gianluca Grilletti</td><td>Andrea Ferrara, Mario Vietri</td></tr>
<tr><td>10.30 - 11.00</td><td>Lorenzo Benedini, Marco Trevisiol</td><td><?php boris_lagnasco(); ?>, Michele Maiolani</td></tr>
<tr><td>11.00 - 11.30</td><td>Alice Cortinovis, Davide Lofano</td><td>Olmo Cerri, Enis Belgacem</td></tr>
<tr><td>11.30 - 12.00</td><td>Fabio Ferri, Andrea Parma</td><td>Davide Decataldo, Francesco Cannizzaro</td></tr>
<tr><td>12.00 - 12.20</td><td>Giada Franz, Federico Glaudo</td><td>Jinglei Zhang, Federica Surace</td></tr>
<tr><td>12.20 - 12.40</td><td>Giulio Rovellini, Matteo Barucco</td><td>Carmelo Mordini, Luca Baroni</td></tr>
<tr><td>12.40 - 13.00</td><td>Guido Lido, Alessandro Pigati</td><td>Michele Fava, Simone Biasco</td></tr>
<tr><td>13.00 - 13.30</td><td>Giovanni Barbarino, Cristoforo Caffi</td><td>Olmo Cerri, Marco Cilibrasi</td></tr>
<tr><td>13.30 - 14.00</td><td>Valentino Liu, Luca Ghidelli</td><td><?php boris_lagnasco(); ?>, Federica Surace</td></tr>
<tr><td>14.00 - 14.30</td><td>Julian Demeio, Matteo de Ceglie</td><td>Luciano Perulli, Enrico Dardanis</td></tr>
<tr><td>14.30 - 15.00</td><td>Marco Barberis, Daniele Semola</td><td>Vasco Cavina, Simone Biasco</td></tr>
<tr><td>15.00 - 15.30</td><td>Davide Lombardo, Giovanni Mascellani</td><td>Ludovico Pontiggia, Alessandro Podo</td></tr>
<tr><td>15.30 - 16.00</td><td>Manuel Berbenni, Andrea Bianchi</td><td>Stanislao Zompì, Caterina Pavoni</td></tr>
<tr><td>16.00 - 16.30</td><td>Giovanni Paolini, Fabrizio Bianchi</td><td>Giulio Mandorli, Tommaso Pajero</td></tr>
<tr><td>16.30 - 17.00</td><td>Alessandro Iraci, Ugo Bindini</td><td>Vasco Cavina, Luca Rigovacca</td></tr>
<tr><td>17.00 - 17.30</td><td>Matteo Verzobio, Federico Scavia</td><td>Matteo Rovere, Giuliano Chiriacò</td></tr>
<tr><td>17.30 - 18.00</td><td>Giulio Bresciani, Marco Marengon</td><td>Davide Napoli, Stefano Bolzonella</td></tr>
<tr><td>18.00 - 18.30</td><td>Francesco Morosi, Edoardo Galfrè</td><td>Anna Zago, Martina Bottacchiari</td></tr>
<tr><td>18.30 - 19.00</td><td>Gennady N. Uraltsev, Carlo Mantegazza</td><td>Giacomo De Palma, Glauco Schettini</td></tr>
<tr><td>19.00 - 19.20</td><td>Laura Capuano, Alessandra Caraceni</td><td>Filippo Revello, Matteo Becchi</td></tr>
<tr><td>19.20 - 19.40</td><td>Aleksandra Baranova, Alessandro Malusà</td><td>Jinglei Zhang, Matteo Ippoliti</td></tr>
<tr><td>19.40 - 20.00</td><td>Marco Robertini, Francesco Giancane</td><td>Davide Napoli, Luca Baroni</td></tr>

</tbody>
</table>


<?php

end_box();
end_html();

?>
