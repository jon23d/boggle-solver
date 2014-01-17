<?
    /**
     * The actual boggle game.  Instantiate this class with a dictionary,
     * then call outputBoardAsHtml & outputWordListAsHtml to see the output.
     *
     * @package Boggle
     * @author Jonathon Deason <jon23d@gmail.com>
     */

    namespace Boggle;

    class Game {

        /** @var Tile[][] $rows     An array of arrays containing Tiles, aka, the board itself */
        private $rows = array();

        /** @var Tree $Tree     A tree structure containing all words in the Dictionary */
        private $Tree = null;

        /** @var string[] $word_list        A list of found words on the board */
        private $word_list = null;

        /** @var int $height            The height of the board */
        private $height = null;

        /** @var int $width             The width of the board */
        private $width = null;

        /** @var int $longest_word_length   The length of the longest word in the loaded dictionary */
        private $longest_word_length = 0;

        /** @var string[] $not_found_prefixes       A list of prefixes NOT FOUND in the dictionary */
        private $not_found_prefixes = array();


        /**
         * Create a new Game
         *
         * @throws \InvalidArgumentException
         *
         * @param Dictionary $Dictionary        The Dictionary to use
         * @param int $width        The number of letters horizontally on the board
         * @param int $height       The number of letters vertically on the board
         *
         * @return Game
         */
        function __construct(Dictionary $Dictionary, $width = 4, $height = 4) {
            // basic validation
            if (
                !is_int($width) || !is_int($height)
                || $width < 1 || $height < 1
            ) {
                throw new \InvalidArgumentException('Height and width must be integers greater than 1');
            }

            $this->initializeBoard($width, $height)
                ->initializeTree($Dictionary)
                ->height = $height;
            $this->width = $width;
            $this->longest_word_length = $Dictionary->getLongestWordLength();

            unset($Dictionary);
        }


        /**
         * Output a simple table representing the board
         *
         * @return Game
         */
        public function outputBoardAsHtml() {
            echo '<table>';
            foreach ($this->rows as $Tiles) {
                echo '<tr>';
                /** @var Tile[] $Tiles */
                foreach ($Tiles as $Tile) {
                    echo '<td>' . $Tile->getCharacter() . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';

            return $this;
        }


        /**
         * Output a four-column table with the found words
         *
         * @return Game
         */
        public function outputWordListAsHtml() {
            // we really don't need to find the words more than once...
            if (is_null($this->word_list)) {

                // the getWords function will recursively search the board, but must be provided the first Tile
                foreach ($this->rows as $Tiles) {
                    /** @var Tile[] $Tiles */
                    foreach ($Tiles as $Tile) {
                        $this->getWords($Tile, $Tile->getCharacter());
                    }
                }
            }

            // separate out the word list into four columns
            $columns = array_chunk($this->word_list, ceil(count($this->word_list) / 4));

            // and show a simple table
            echo '<table><tr>';
            foreach ($columns as $column) {
                echo '<td>' . implode('<br />', $column) . '</td>';
            }
            echo '</tr></table>';

            return $this;
        }


        /**
         * Initialize the board
         *
         * @param int $width
         * @param int $height
         *
         * @return Game
         */
        private function initializeBoard($width, $height) {
            for ($row = 0; $row < $height; $row++) {
                for ($column = 0; $column < $width; $column++) {
                    $this->rows[$row][$column] = new Tile(chr(97 + mt_rand(0, 25)), $row, $column);
                }
            }

            return $this;
        }


        /**
         * Initialize the Tree
         *
         * @param Dictionary $Dictionary
         *
         * @param Dictionary $Dictionary
         *
         * @return Game
         */
        private function initializeTree(Dictionary $Dictionary) {
            $Tree = new Tree();

            foreach ($Dictionary->getWords($this->getLetters()) as $word) {
                $Tree->addWord($word);
            }

            $this->Tree = $Tree;
            return $this;
        }


        /**
         * Get a string representing all of the letters on this board
         *
         * @return string
         */
        private function getLetters() {
            $return_val = '';

            foreach ($this->rows as $Tiles) {
                /** @var Tile[] $Tiles */
                foreach ($Tiles as $Tile) {
                    /** @var Tile $Tile */
                    $return_val .= $Tile->getCharacter();
                }
            }

            return $return_val;
        }


        /**
         * Get an array of words for the provided Tile with the provided prefix found in the Tree
         *
         * @param Tile $Tile
         * @param string $prefix
         *
         * @return void
         */
        private function getWords(Tile $Tile, $prefix) {
            // If this prefix has already been marked as not found, then just return
            if (in_array($prefix, $this->not_found_prefixes)) {
                return;
            }

            // If this Tile ends a word, add it to the list, but continue processing any potential neighbors
            $does_word_exist = $this->Tree->doesWordExist($prefix);
            if (true === $does_word_exist) {
                $this->word_list[] = $prefix;

            // If the prefix itself doesn't exist, then add it to the list of missing prefixes, and return
            } elseif (Dictionary::MISSING_PREFIX === $does_word_exist) {
                $this->not_found_prefixes[] = $prefix;
                return;
            }

            // There is no reason to continue recursion if we have met the length of the longest word in the dictionary
            if (strlen($prefix) === $this->longest_word_length) {
                return;
            }

            // mark this Tile as used to prevent an infinite loop
            $Tile->setIsUsed(true);

            // loop through the neighboring Tiles, checking the word list with the non-used ones appended to the prefix
            $Neighbors = $this->getNeighbors($Tile);
            foreach ($Neighbors as $Neighbor) {
                if (!$Neighbor->getIsUsed()) {
                    $this->getWords($Neighbor, $prefix . $Neighbor->getCharacter());
                }
            }

            // We are done with this iteration, allow this Tile to be used in future combinations
            $Tile->setIsUsed(false);
        }


        /**
         * Get the all neighboring Tiles to the provided Tile
         *
         * @param Tile $Tile
         *
         * @return Tile[]
         */
        private function getNeighbors(Tile $Tile) {
            $Tiles = array();

            $row = $Tile->getRow();
            $column = $Tile->getColumn();

            $min_row = ($row - 1 < 0) ? 0 : $row - 1;
            $min_column = ($column - 1 < 0) ? 0 : $column - 1;
            $max_row = ($row + 1 > $this->height) ? $this->height : $row + 1;
            $max_column = ($column + 1 > $this->width) ? $this->width : $column + 1;

            for ($r = $min_row; $r <= $max_row; $r++) {
                for ($c = $min_column; $c <= $max_column; $c++) {
                    if ($r == $row && $c == $column) continue;

                    if (isset($this->rows[$r][$c])) {
                        $Tiles[] = $this->rows[$r][$c];
                    }
                }
            }

            return $Tiles;
        }

    }