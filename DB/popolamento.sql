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


INSERT INTO foto_profilo VALUES
(1,'pathFoto','alt foto profilo di pippo'),
(2,'pathFoto','alt foto profilo di pippo'),
(3,'pathFoto','alt foto profilo di pippo'),
(4,'pathFoto','alt foto profilo di pippo'),
(5,'pathFoto','alt foto profilo di pippo'),
(6,'pathFoto','alt foto profilo di pippo'),
(7,'pathFoto','alt foto profilo di pippo'),
(8,'pathFoto','alt foto profilo di pippo'),
(9,'pathFoto','alt foto profilo di pippo'),
(10,'pathFoto','alt foto profilo di pippo'),
(11,'pathFoto','alt foto profilo di pippo'),
(12,'pathFoto','alt foto profilo di pippo');


INSERT INTO utenti VALUES
(1,'pipinoIlBreve','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',1,'pippo.breve@gmail.com',0),
(2,'mammaInformata','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',2,'mamma.forno@virgilio.it',0),
(3,'giovanna97','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',3,'giovanna97@hotmail.it',0),
(4,'pablo89','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',4,'pablo89@drugs.co',0),
(5,'carletto_the_best','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',5,'carlo.franco@virgilio.it',0),
(6,'giovanniReader','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',6,'giovanni.storti@msn.it',0),
(7,'divoratore_di_libri','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',7,'francesco.barocco@gmail.com',0),
(8,'francescaM99','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',8,'francy.emme@virgilio.it',0),
(9,'antoBaddio','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',9,'antonello.baldino@cafoscari.it',0),
(10,'troloFre','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',10,'federico.trolesio@gmail.com',0),
(11,'kaiser_lillo38','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',11,'luca.falsonese@virgilio.it',0),
(12,'simo99DR','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8',12,'simone.dr.dre@gmail.com',0);


INSERT INTO recensioni VALUES
(1,'2021-01-10 10:15:30',3,1,1,'libro bello ma non ci vivrei. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(2,'2021-01-09 10:15:30',4,1,2,'libro molto bello. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(3,'2021-01-05 10:15:30',2,1,3,'libro bruttino. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(4,'2021-01-06 10:15:30',1,1,4,'libro bello ma non ci vivrei. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(5,'2021-01-06 10:15:30',5,1,5,'libro bello ma non ci vivrei. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(6,'2021-01-01 10:15:30',1,1,6,'libro bello ma non ci vivrei. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(7,'2021-01-15 10:15:30',2,1,7,'libro bruttino. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(8,'2021-01-20 10:15:30',4,1,8,'libro bello ma non ci vivrei. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(9,'2021-01-25 10:15:30',3,1,9,'libro bello ma non ci vivrei. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(10,'2021-01-30 10:15:45',3,1,10,'libro bello ma non ci vivrei. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(11,'2021-01-30 10:10:30',2,1,11,'libro bruttino. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.'),
(12,'2021-01-30 08:15:30',3,1,12,'libro bello ma non ci vivrei. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.');
