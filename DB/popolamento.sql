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
(1,'pathFoto','alt foto profilo di pippo');

INSERT INTO utenti VALUES
(1,'pipinoIlBreve','dwni32423r9wejfioc32nx',1,'pippo@breve.it',0);

INSERT INTO recensioni VALUES
(1,'2021-01-10 10:15:30',3,1,1,'libro bello ma non ci vivrei. Recensione libro. Recensione libro.Recensione libro. Recensione libro. Recensione libro. Recensione libro.');
