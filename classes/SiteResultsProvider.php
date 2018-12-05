<?php

class SiteResultsProvider {
    private $_conn;

    public function __construct(PDO $conn)
    {
        $this->_conn = $conn;
    }

    public function getNumResults($term) {
        $stmt = 'SELECT COUNT(*) as total 
                FROM sites 
                WHERE title LIKE :term 
                OR url LIKE :term2
                OR keywords LIKE :term3
                OR description LIKE :term4';

        $query = $this->_conn->prepare($stmt);

        $searchTerm = "%$term%";
        $query->bindParam(':term', $searchTerm);
        $query->bindParam(':term2', $searchTerm);
        $query->bindParam(':term3', $searchTerm);
        $query->bindParam(':term4', $searchTerm);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getResultsHTML(int $page_num, int $page_size, string $term) {
        $fromLimit = ($page_num - 1) * $page_size;
        $page_size = intval($page_size);

        $stmt = 'SELECT * 
                FROM sites 
                WHERE title LIKE :term 
                OR url LIKE :term2
                OR keywords LIKE :term3
                OR description LIKE :term4
                ORDER BY clicks DESC
                OFFSET :from_limit ROWS
                FETCH NEXT :page_size ROWS ONLY';

        $query = $this->_conn->prepare($stmt);

        $searchTerm = "%$term%";
        $query->bindParam(':term', $searchTerm);
        $query->bindParam(':term2', $searchTerm);
        $query->bindParam(':term3', $searchTerm);
        $query->bindParam(':term4', $searchTerm);
        $query->bindParam(':from_limit', $fromLimit,PDO::PARAM_INT);
        $query->bindParam(':page_size', $page_size, PDO::PARAM_INT);


        $query->execute();

        $resultsHTML = "<div class='site-results'>";

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $url = $row['url'];
            $title = $this->trimField($row['title'], 65);
            $description = $this->trimField($row['description'], 65);

            $resultsHTML .= "<div class='result-container'>
                                <h3 class='title'>
                                    <a data-link-id='$id' class='result' href='$url'>$title</a>
                                </h3>
                                <span class='url'>$url</span>
                                <span class='description'>$description</span>
                            </div>";
        }

        $resultsHTML .= '</div>';

        return $resultsHTML;
    }

    private function trimField($string, $characterLimit) {
        $dots = strlen($string) > $characterLimit ? '...' : '';
        return substr($string, 0, $characterLimit) . $dots;
    }
}