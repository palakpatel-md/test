CREATE TABLE IF NOT EXISTS `tags` (
      id int(11) NOT NULL AUTO_INCREMENT,
      tags varchar(255) DEFAULT NULL,
      category varchar(255) DEFAULT NULL,
      catid int(11) NOT NULL,
      UNIQUE KEY id (id)
    )ENGINE=InnoDB DEFAULT CHARSET=latin1;