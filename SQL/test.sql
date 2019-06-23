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

SELECT
  id, username, firstname,
  lastname, email, profilePhoto,
  type, activate
FROM user;