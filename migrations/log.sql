CREATE TABLE IF NOT EXISTS `log` (
    `id` BigInt( 0 ) UNSIGNED AUTO_INCREMENT NOT NULL,
    `info` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `status` VarChar( 32 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `uri` VARCHAR( 255 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
    PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci
ENGINE = InnoDB;
