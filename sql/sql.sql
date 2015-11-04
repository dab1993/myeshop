USE myeshop

SET NAMES utf8;

CREATE TABLE it_admin
(
---主键信息
 admin_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
---自然信息 
 admin_name VARCHAR(20) NOT NULL,
 admin_pass CHAR(32) NOT NULL COMMENT 'md5加密',
 email VARCHAR(100) DEFAULT '',
---登录信息
last_login INT DEFAULT 0 COMMENT '时间戳格式的最后登录时间',
last_ip VARCHAR(15) COMMENT 'ip地址',
last_ip_int INT COMMENT 'ip地址整形保存形式'
---密码认证信息
---权限信息
)ENGINE MYISAM CHARSET = 'utf8'

create table it_goods
(
goods_id int primary key auto_increment,
goods_sn char(10) comment '商品货号',
goods_name varchar(50) comment '名称',
cat_id int unsigned comment '分类',
shop_price decimal(10,2) comment '本店售价',
market_price decimal(10,2) comment '市场售价',
add_time int comment '添加时间',
goods_number int comment '商品库存',
image_ori varchar(100) comment '原始图片路径',
image_thumb varchar(100) comment '缩略图',
goods_stutas int comment '位运算保存 精品 热销 新品',
is_on_sale tinyint comment '1上架  0下架',
update_time timestamp default current_timestamp comment '更新时间'
)charset utf8;
