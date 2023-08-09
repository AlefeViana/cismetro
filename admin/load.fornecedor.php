SELECT tbfornecedor.CdForn, tbfornespec.CdEspec, tbespecproc.NmEspecProc
from tbfornecedor, tbfornespec, tbespecproc
WHERE tbfornecedor.CdForn = tbfornespec.CdForn
AND tbespecproc.CdEspecProc = tbfornespec.CdEspec
ORDER BY NmEspecProc