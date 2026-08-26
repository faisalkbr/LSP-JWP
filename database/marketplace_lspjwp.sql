-- =====================================================================
-- LSP Junior Web Programmer - Skenario: MarketPlace Sederhana
-- Script DDL MySQL untuk Reverse Engineering di PowerDesigner
-- Target DBMS : MySQL 5.0 / 8.0 (InnoDB)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS marketplace
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE marketplace;

-- ---------------------------------------------------------------------
-- Tabel: users
-- Menyimpan data pengguna sistem, dibedakan oleh kolom role.
-- ---------------------------------------------------------------------
CREATE TABLE users (
    id_user      INT             NOT NULL AUTO_INCREMENT,
    nama_user    VARCHAR(100)    NOT NULL,
    email        VARCHAR(100)    NOT NULL,
    password     VARCHAR(255)    NOT NULL,
    role         ENUM('pembeli','penjual')    NOT NULL,
    tgl_daftar   DATETIME        NOT NULL,
    foto_profil  VARCHAR(255)    NULL,
    status_user  ENUM('aktif','tidak_aktif')  NOT NULL DEFAULT 'aktif',
    created_at   TIMESTAMP       NULL,
    updated_at   TIMESTAMP       NULL,
    CONSTRAINT pk_users PRIMARY KEY (id_user),
    CONSTRAINT uq_users_email UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Tabel: produk
-- Katalog produk. Satu penjual dapat memiliki banyak produk.
-- ---------------------------------------------------------------------
CREATE TABLE produk (
    id_product      INT             NOT NULL AUTO_INCREMENT,
    penjual_id      INT             NOT NULL,
    nama_product    VARCHAR(150)    NOT NULL,
    deskripsi       TEXT            NULL,
    harga           DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    stok            INT             NOT NULL DEFAULT 0,
    kategori        VARCHAR(50)     NULL,
    gambar_product  VARCHAR(255)    NULL,
    status_product  ENUM('tersedia','habis')  NOT NULL DEFAULT 'tersedia',
    created_at      TIMESTAMP       NULL,
    updated_at      TIMESTAMP       NULL,
    CONSTRAINT pk_produk PRIMARY KEY (id_product),
    CONSTRAINT fk_produk_penjual FOREIGN KEY (penjual_id)
        REFERENCES users (id_user)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_produk_penjual ON produk (penjual_id);

-- ---------------------------------------------------------------------
-- Tabel: orders
-- Header pesanan. Satu pembeli dapat membuat banyak order.
-- Catatan: nama tabel dijamakkan menjadi "orders" karena ORDER
-- adalah reserved word MySQL sekaligus mengikuti konvensi Laravel.
-- ---------------------------------------------------------------------
CREATE TABLE orders (
    id_order           INT             NOT NULL AUTO_INCREMENT,
    pembeli_id         INT             NOT NULL,
    tgl_order          DATETIME        NOT NULL,
    total_harga        DECIMAL(14,2)   NOT NULL DEFAULT 0.00,
    status_order       ENUM('menunggu_bayar','menunggu_konfirmasi','diproses',
                            'dikirim','selesai','dibatalkan')
                                       NOT NULL DEFAULT 'menunggu_bayar',
    alamat_pengiriman  TEXT            NOT NULL,
    bukti_bayar        VARCHAR(255)    NULL,
    created_at         TIMESTAMP       NULL,
    updated_at         TIMESTAMP       NULL,
    CONSTRAINT pk_orders PRIMARY KEY (id_order),
    CONSTRAINT fk_orders_pembeli FOREIGN KEY (pembeli_id)
        REFERENCES users (id_user)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_orders_pembeli ON orders (pembeli_id);

-- ---------------------------------------------------------------------
-- Tabel: order_detail
-- TAMBAHAN DI LUAR DAFTAR SOAL.
-- Diperlukan karena satu order dapat berisi lebih dari satu produk
-- (halaman Keranjang & Checkout). Tanpa tabel ini, quantity per produk
-- pada satu order tidak dapat disimpan.
-- Hapus blok ini jika ingin persis mengikuti daftar tabel pada soal.
-- ---------------------------------------------------------------------
CREATE TABLE order_detail (
    id_order_detail  INT             NOT NULL AUTO_INCREMENT,
    order_id         INT             NOT NULL,
    product_id       INT             NOT NULL,
    quantity         INT             NOT NULL DEFAULT 1,
    harga_satuan     DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    subtotal         DECIMAL(14,2)   NOT NULL DEFAULT 0.00,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    CONSTRAINT pk_order_detail PRIMARY KEY (id_order_detail),
    CONSTRAINT fk_detail_order FOREIGN KEY (order_id)
        REFERENCES orders (id_order)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_detail_produk FOREIGN KEY (product_id)
        REFERENCES produk (id_product)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_detail_order ON order_detail (order_id);
CREATE INDEX idx_detail_produk ON order_detail (product_id);
