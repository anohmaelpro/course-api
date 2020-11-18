import React from 'react';

const Pagination  = ({currentPage, itemPerPage, dataLength, onPageChange}) => {

          // nombre de pages sur le site en fonction du nombre d'élément recu
          const pagesCount = Math.ceil(dataLength / itemPerPage) ;
          const pages = [] ;
          for (let i = 1; i <= pagesCount; i++) {
                    pages.push(i) ;
          }


          return ( 
                    <>
                              <div id="paginationCustomers">
                                        <ul className="pagination pagination-sm">
                                                  <li className={"page-item" + (currentPage === 1 && " disabled")}>
                                                            <button className="page-link" onClick={() => onPageChange(1)}>
                                                                      &laquo;&laquo;
                                                            </button>
                                                  </li>
                                                  <li className={"page-item" + (currentPage === 1 && " disabled")}>
                                                            <button className="page-link" onClick={() => onPageChange(currentPage - 1)}>
                                                                      &laquo;
                                                            </button>
                                                  </li>
                                                  {pages.map(page => (
                                                            <li key={page} className={"page-item" + (currentPage === page && " active")}>
                                                                      <button className="page-link" onClick={() => onPageChange(page)}>
                                                                                {page}
                                                                      </button>
                                                            </li>
                                                  ))}
                                                  <li className={"page-item" + (currentPage === pagesCount && " disabled")}>
                                                            <button className="page-link" onClick={() => onPageChange(currentPage + 1)}>
                                                                      &raquo;
                                                            </button>
                                                  </li>
                                                  <li className={"page-item" + (currentPage === pagesCount && " disabled")}>
                                                            <button className="page-link" onClick={() => onPageChange(pagesCount)}>
                                                                      &raquo;&raquo;
                                                            </button>
                                                  </li>
                                        </ul>
                              </div>
                    </>
          );
};

Pagination.getData =(items, currentPage, itemPerPage) => {
          const start = currentPage * itemPerPage - itemPerPage;
          return items.slice(start, start + itemPerPage) ;
}
 
export default Pagination;