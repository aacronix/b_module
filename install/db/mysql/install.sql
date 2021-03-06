CREATE TABLE IF NOT EXISTS b_weather_widget
(
    ID INT NOT NULL AUTO_INCREMENT,
    WIDGET_ID VARCHAR(15) NOT NULL DEFAULT '',
    ACTIVE BOOL DEFAULT TRUE,
    NAME VARCHAR(100) NULL DEFAULT '',
    TEMPLATE_NAME VARCHAR(100) NULL DEFAULT '',
    SUPER BOOL DEFAULT FALSE,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS b_weather_widget_option
(
  ID INT NOT NULL AUTO_INCREMENT,
	WIDGET_ID VARCHAR(15) NOT NULL DEFAULT '',
	NAME VARCHAR(100) NULL,
	VALUE VARCHAR(200) NULL DEFAULT '',
	PRIMARY KEY (ID)
);

INSERT INTO b_weather_widget(WIDGET_ID, NAME, SUPER) VALUES ('w_0', 'Default', TRUE);
INSERT INTO b_weather_widget_option(WIDGET_ID, NAME) VALUES ('w_0', 'weather_provider');