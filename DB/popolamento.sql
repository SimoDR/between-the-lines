#
# Popolamento Tabella generi
#
INSERT INTO generi VALUES 
(1, 'fantasy'),
(2, 'epica'),
(3, 'fumetto'),
(4, 'narrativa'),
(5, 'romanzo'),
(6, 'satira'),
(7, 'fantascienza'),
(8, 'favola'),
(9, 'giallo');

#
# Popolamento Tabella autori
#
INSERT INTO autori VALUES 
(1, 'Luigi','Pirandello','1867-06-28','1936-12-10'),
(2, 'Arthur Conan','Doyle','1859-05-22','1930-07-07'),
(3, 'Luis','Sepúlveda','1949-10-04','2020-04-16'),
(4, 'Jack', 'Kerouac', '1922-03-12', '1969-10-21');

#
# Popolamento Tabella libri
#
INSERT INTO libri VALUES 
(1, 'Il fu Mattia Pascal',1,5,'Allo sfaccendato bibliotecario Mattia Pascal il caso offre una possibilità clamorosa: azzerare il proprio passato e ricominciare da capo. Moglie, suocera e amici lo riconoscono nel cadavere di un suicida e lo credono morto. Ricco grazie a una vincita al gioco, può inventarsi la nuova identità di Adriano Meis. Ma la libertà appena acquisita si rivela in realtà una ferrea prigione. Umoristico e grottesco scandaglio della realtà piccolo-borghese, il capolavoro di Pirandello evidenzia l''impossibilità per l''uomo di essere davvero artefice del proprio destino.'),
(2, 'Uno studio in rosso',2,9,'Pubblicato nel 1887, "Uno studio in rosso" segnò la nascita di Sherlock Holmes, il più famoso personaggio della letteratura gialla, che trasformò il suo autore, Sir Arthur Conan Doyle, da medico senza fortuna in autore destinato alla storia. John Watson è un ex medico militare appena tornato dalla guerra nelle colonie britanniche. Parlando col suo giovane assistente Stamford, dichiara di essere in cerca di un alloggio a buon prezzo; Stamford gli menziona Sherlock Holmes, in cerca di un coinquilino con cui dividere le spese di un appartamento. Il loro incontro è memorabile: da una semplice occhiata Holmes indovina il mestiere di Watson, lasciando questi esterrefatto. I due entrano subito in sintonia, e prendono in affitto l''appartamento al 221bdi Baker Street. In una casa disabitata per motivi sanitari, viene trovato il cadavere di Enoch J. Drebber. Il corpo non presenta ferite, ma tutt''intorno vi è sangue ovunque; quello stesso sangue compone sul muro la parola rache. Sherlock Holmes riuscirà a individuare l''omicida, risolvendo brillantemente un caso che sembrava insolubile. Il primo episodio di una fra le saghe più celebrate di tutti i tempi, una trama a enigma tra Londra e gli Stati Uniti che racconta l''incontro fra Sherlock Holmes e il dottor Watson.'),
(3, 'Storia di una gabbianella e del gatto che le insegnò a volare',3,4,'Dopo essere capitata in una macchia di petrolio nelle acque del mar Nero, la gabbiana Kengah atterra in fin di vita sul balcone del gatto Zorba, al quale strappa tre promesse solenni: di non mangiare l''uovo che lei sta per deporre, di averne cura e di insegnare a volare al piccolo che nascerà. Così, alla morte di Kengah, Zorba cova l''uovo e, quando si schiude, accoglie la neonata gabbianella nella buffa e affiatata comunità felina del porto di Amburgo. Ma come può un gatto insegnare a volare? Per mantenere la terza promessa, Zorba dovrà ricorrere all''aiuto di tutti, anche a quello di un uomo. In una storia che ha la grazia di una fiaba e la forza di una parabola, il grande scrittore cileno tocca i temi a lui più cari: l''amore per la natura, la generosità disinteressata e la solidarietà, anche fra «diversi».'),
(4, 'Sulla Strada',4,5,'Narra una serie di viaggi dell''autore in automobile attraverso gli Stati Uniti, in parte con il suo amico Neal Cassady e in parte in autostop.');

#
# Popolamento Tabella copertine
#
INSERT INTO copertine VALUES 
(1,1,'../img/copertina_1.jpg','caricatura di Luigi Pirandello'),
(2,2,'../img/copertina_2.jpg','Sherlock Holmes guarda una scritta enigmatica ''RACHE'' con la lente di ingrandimento e fumando una pipa'),
(3,3,'../img/copertina_3.jpg','un gatto con un collare osserva un gabbiano volare con le ali spiegate'),
(4,4,'../img/copertina_4.jpg','insegna di un motel che si staglia nel deserto');

#
# Popolamento Tabella foto_profilo
#
INSERT INTO foto_profilo VALUES 
(1, '../img/icona_libro_rosso.png', 'libro rosso'),
(2, '../img/icona_libro_verde.png', 'libro verde'),
(3, '../img/icona_libro_blu.png', 'libro blu'),
(4, '../img/icona_libro_giallo.png', 'libro giallo');

#
# Popolamento Tabella utenti
#
INSERT INTO utenti VALUES 
(1, 'admin', 'admin',1,'admin@admin.it','1'),
(2, 'utente', 'utente',2,'utente@utente.it','0'),
(3, 'francy99', 'francy99',3,'francy99@gmail.com','0'),
(4, 'antobaddo', 'antobaddo',4,'antobaddo@gmail.com','0'),
(5, 'kayser', 'kayser',1,'kayser@unipd.it','0'),
(6, 'simonetta', 'simonetta',2,'simonetta@yahoo.it','0');

#
# Popolamento Tabella recensioni
# 
INSERT INTO recensioni VALUES
(NULL, '2020-12-26 12:12:21', 5, 1, 2, 'Per me Pirandello è sempre una certezza, con la sua ironia tratta temi davvero particolari, ma soprattutto attuali'),
(NULL, '2020-12-26 12:12:22', 5, 2, 6, 'Un classico intramontabile, da leggere e rileggere'),
(NULL, '2020-12-26 11:33:33', 4, 1, 3, 'Non può mancare nella formazione di ciascuno di noi. Divertente, dinamico, a tratti esilarante. Consigliato!!'),
(NULL, '2020-12-27 13:43:11', 5, 2, 5, 'Il mitico Sherlock fa il suo ingresso… uno dei gialli più famosi e ovviamente consigliatissimo.'),
(NULL, '2020-12-27 14:33:23', 4, 2, 4, 'Suggerisco questo come primo romanzo a chi si approccia a Sherlock Holmes per la prima volta.. Scoprirete il perché leggendo!'),
(NULL, '2021-01-03 16:06:54', 4, 2, 2, 'Il primo libro del celebre Sherlock Holmes. È interessante il suo metodo di investigazione, che pare molto retró, specialmente dato che oggi abbiamo a nostra disposizione tecniche avanguardistiche, ma resta, a livello narrativo, decisamente intrigante ed efficace.'),
(NULL, '2021-01-03 22:06:54', 4, 1, 4, 'Un''ottima lettura... con i grandi classici non si sbaglia mai'),
(NULL, '2021-01-05 21:12:23', 4, 3, 3, 'Bellissimo racconto, che ti emoziona e ti insegna a riflettere'),
(NULL, '2021-01-05 14:09:11', 5, 1, 5, 'Diciamoci la verità: chi di noi non ha sognato, almeno una volta, di avere la possibilità di sparire dalla circolazione e di iniziare una nuova vita da capo? Con un libro di una genialità unica, Pirandello trasforma questa fantasia in realtà, narrandoci le vicissitudini di Mattia Pascal.'),
(NULL, '2021-01-08 23:56:09', 4, 3, 2, 'Letto per la prima volta alle elementari e ho un ricordo bellissimo di questa storia stupenda. Fiumi di lacrime e una morale meravigliosa.'),
(NULL, '2021-01-08 23:32:08', 3, 1, 6, 'Un classico della letteratura. Consigliatissimo, ma a parer mio in alcuni punti troppo lento e noioso'),
(NULL, '2021-01-10 22:32:08', 5, 4, 3,'Uno dei libri più belli che abbia mai letto. Mi sembrava di essere in viaggio con Sal. Super consigliato ai pazzi, ai viaggiatori e ai sognatori!');

# CONTROLLO
# mattia pascal : 5 + 4 + 4 + 5 + 3 -> 4,2
# studio in rosso 5 + 5 + 4 + 4 -> 4,5
# gabbianella 4 + 4 -> 4
# sulla strada 5 -> 5

