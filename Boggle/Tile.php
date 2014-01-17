<?
    namespace Boggle;

    class Tile {

        /** @var string $letter     The character that this Tile represents */
        private $letter = null;

        /** @var bool $is_used      A flag indicating whether this Tile has already been used */
        private $is_used = false;

        /** @var int $row_number    The row number on the board for this Tile */
        private $row_number = 0;

        /** @var int $column_number The column number on the board for this Tile */
        private $column_number = 0;

        /**
         * Create a new Tile
         *
         * @throws \InvalidArgumentException
         *
         * @param string $character             The character this Tile represents
         * @param int $row_number               The row number for this Tile, zero-indexed
         * @param int $column_number            The column number for this Tile, zero-indexed
         *
         * @return Tile
         */
        public function __construct($character, $row_number, $column_number) {
            if (strlen($character != 1) || 1 !== preg_match('/^[a-z]{1}$/', strtolower($character))) {
                throw new \InvalidArgumentException('Character must be one letter in length');
            }

            $this->letter = strtolower($character);
            $this->row_number = $row_number;
            $this->column_number = $column_number;
        }


        /**
         * Set whether or not this Tile is considered used
         *
         * @param bool $true_or_false
         *
         * @return Tile
         */
        public function setIsUsed($true_or_false) {
            $this->is_used = ($true_or_false) ? true : false;
            return $this;
        }


        /**
         * Get whether or not this Tile is considered used
         *
         * @return bool
         */
        public function getIsUsed() {
            return $this->is_used;
        }

        /**
         * Get the character that this Tile represents
         *
         * @return string
         */
        public function getCharacter() {
            return $this->letter;
        }

    }