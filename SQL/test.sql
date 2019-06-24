use james;

INSERT INTO user (
  username, firstname,
  lastname, email, password
) VALUES (
  "wvxbs",
  "Gabriel",
  "Ferreira",
  "gabriel.ferreira@gmail.com",
  "123@gab"
);

INSERT INTO `video` (
	title, path, thumbnailPhoto, userId
) VALUES (
	'A Gravidade NÃO é uma Força',
    '/assets/video/data/A_Gravidade_NÃO_é_uma_Força.mp4',
    '/assets/video/thumbnail/A_Gravidade_NÃO_é_uma_Força.jpg',
    1
);

SELECT
      id, username, firstname,
      lastname, email, profilePhoto,
      type, activate
    FROM user u
    WHERE
      u.username = 'gmochi56' AND
      u.password = '123g'
    LIMIT 0,1;

SELECT
  id, username, firstname,
  lastname, email, profilePhoto,
  type, activate
FROM user;

SELECT * FROM video;