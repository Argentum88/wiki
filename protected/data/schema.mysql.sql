CREATE DATABASE wiki
  CHARACTER SET utf8
  COLLATE utf8_general_ci;

USE wiki;

/* Tables */
CREATE TABLE page (
  id        int AUTO_INCREMENT NOT NULL,
  title     varchar(200) NOT NULL,
  content   text,
  parent    int,
  children  text,
  url       text NOT NULL,
  PRIMARY KEY (id)
) ENGINE = InnoDB;

/* Data for table "page" */
INSERT INTO page (id, title, content, parent, children, url) VALUES
(1, 'Животные', 'Животные!', NULL, 'cat,dog', 'animal'),
(2, 'Кот', 'Коты это круто', 1, NULL, 'cat'),
(3, 'собака', 'Собачки это ваще крутяк!', 1, NULL, 'dog');